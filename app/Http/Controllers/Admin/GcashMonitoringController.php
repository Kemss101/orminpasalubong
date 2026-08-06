<?php

namespace App\Http\Controllers\Admin;

use App\Models\GcashTransaction;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GcashMonitoringController extends Controller
{
    /**
     * Show GCash transactions dashboard
     */
    public function index(Request $request)
    {
        $query = GcashTransaction::with('user', 'order')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by reference number, user name, or email
        if ($request->search) {
            $query->where('reference_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
        }

        $transactions = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'total_transactions' => GcashTransaction::count(),
            'pending_count' => GcashTransaction::where('status', 'pending')->count(),
            'completed_count' => GcashTransaction::where('status', 'completed')->count(),
            'failed_count' => GcashTransaction::where('status', 'failed')->count(),
            'total_amount_completed' => GcashTransaction::where('status', 'completed')->sum('amount'),
            'pending_amount' => GcashTransaction::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.gcash-monitoring', compact('transactions', 'stats'));
    }

    /**
     * Show transaction details
     */
    public function show(GcashTransaction $transaction)
    {
        return response()->json([
            'id' => $transaction->id,
            'reference_number' => $transaction->reference_number,
            'user' => [
                'id' => $transaction->user->id,
                'name' => $transaction->user->name,
                'email' => $transaction->user->email,
                'phone' => $transaction->user->address,
            ],
            'order' => $transaction->order ? [
                'id' => $transaction->order->id,
                'items_count' => $transaction->order->items->count(),
                'total_amount' => $transaction->order->total_amount,
            ] : null,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
            'type' => $transaction->type,
            'gcash_receipt_number' => $transaction->gcash_receipt_number,
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            'verified_at' => $transaction->verified_at?->format('Y-m-d H:i:s'),
            'notes' => $transaction->notes,
        ]);
    }

    /**
     * Update transaction status
     */
    public function updateStatus(Request $request, GcashTransaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $transaction->update([
            'status' => $validated['status'],
            'verified_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        if ($validated['notes']) {
            $existingNotes = $transaction->notes ?? '';
            $timestamp = now()->format('Y-m-d H:i:s');
            $newNotes = $existingNotes ? $existingNotes . "\n[$timestamp] " . $validated['notes'] : "[$timestamp] " . $validated['notes'];
            $transaction->update(['notes' => $newNotes]);
        }

        // If completing payment, update order and add cashback
        if ($validated['status'] === 'completed' && $transaction->order) {
            $transaction->order->markAsPaid($transaction);
            
            // Add cashback
            $cashbackAmount = $transaction->order->calculateCashback();
            $cashback = $transaction->order->user->getOrCreateCashback();
            $cashback->addCashback($cashbackAmount, $transaction->order->id, 'Purchase cashback');

            // Create delivery tracking if not exists
            if (!$transaction->order->deliveryTracking) {
                $transaction->order->deliveryTracking()->create([
                    'status' => 'Pending',
                ]);
            }
        }

        if ($validated['status'] === 'failed' && $transaction->order) {
            $transaction->order->update(['payment_status' => 'failed']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction status updated successfully.',
        ]);
    }

    /**
     * Export transactions report
     */
    public function export(Request $request)
    {
        $query = GcashTransaction::with('user', 'order')->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->get();

        // Generate CSV
        $csv = "Reference Number,User Name,Email,Order ID,Amount,Status,Type,Receipt Number,Verified At,Created At\n";

        foreach ($transactions as $transaction) {
            $userName = $transaction->user?->name ?? 'N/A';
            $userEmail = $transaction->user?->email ?? 'N/A';
            $orderId = $transaction->order_id ?? 'N/A';
            $receiptNumber = $transaction->gcash_receipt_number ?? 'N/A';
            $verifiedAt = $transaction->verified_at?->format('Y-m-d H:i:s') ?? 'N/A';
            $createdAt = $transaction->created_at?->format('Y-m-d H:i:s') ?? 'N/A';

            $csv .= $transaction->reference_number . ',';
            $csv .= $userName . ',';
            $csv .= $userEmail . ',';
            $csv .= $orderId . ',';
            $csv .= '₱' . $transaction->amount . ',';
            $csv .= $transaction->status . ',';
            $csv .= $transaction->type . ',';
            $csv .= $receiptNumber . ',';
            $csv .= $verifiedAt . ',';
            $csv .= $createdAt . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="gcash-transactions-' . now()->format('Y-m-d-H-i-s') . '.csv"',
        ]);
    }

    /**
     * Get transaction statistics by date
     */
    public function statistics(Request $request)
    {
        $query = GcashTransaction::selectRaw('DATE(created_at) as date, status, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('date', 'status')
            ->orderBy('date', 'desc');

        if ($request->days) {
            $query->where('created_at', '>=', now()->subDays($request->days));
        }

        $stats = $query->get();

        return response()->json([
            'statistics' => $stats,
            'summary' => [
                'total_transactions' => GcashTransaction::count(),
                'completed_transactions' => GcashTransaction::where('status', 'completed')->count(),
                'pending_transactions' => GcashTransaction::where('status', 'pending')->count(),
                'failed_transactions' => GcashTransaction::where('status', 'failed')->count(),
                'total_amount_completed' => GcashTransaction::where('status', 'completed')->sum('amount'),
            ],
        ]);
    }
}
