<?php

namespace App\Http\Controllers;

use App\Models\GcashTransaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Show payment page for order
     */
    public function show(Order $order)
    {
        $authUser = auth()->user();

        if (!$authUser || (!$authUser->isAdmin() && (int) $order->user_id !== (int) $authUser->id)) {
            abort(403);
        }

        $transaction = $order->gcashTransaction()->latest('id')->first();

        return view('payment.payment-form', [
            'order' => $order,
            'transaction' => $transaction,
            'gcashUrl' => $transaction?->getGcashUrl(),
        ]);
    }

    /**
     * Process GCash payment initiation
     */
    public function processGcash(Request $request, Order $order)
    {
        $authUser = $request->user();

        if (!$authUser || (int) $order->user_id !== (int) $authUser->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'gcash_number' => 'required|regex:/^[0-9]{11}$/',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $grandTotal = (float) $order->total_amount + (float) ($order->shipping_fee ?? 0);

        if ((float) $validated['amount'] != $grandTotal) {
            return response()->json(['error' => 'Amount does not match order total'], 400);
        }

        // Create pending transaction
        $referenceNumber = 'GCH-' . now()->format('YmdHis') . '-' . Str::random(8);
        
        $transaction = GcashTransaction::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'reference_number' => $referenceNumber,
            'amount' => $grandTotal,
            'status' => 'pending',
            'type' => 'payment',
            'notes' => 'GCash payment via mobile number: ' . substr($validated['gcash_number'], -4),
        ]);

        return response()->json([
            'status' => 'success',
            'reference_number' => $referenceNumber,
            'transaction_id' => $transaction->id,
            'amount' => $grandTotal,
            'gcash_url' => 'gcash://pay?reference=' . urlencode($referenceNumber) . '&amount=' . urlencode(number_format($grandTotal, 2, '.', '')),
            'message' => 'Payment pending. Please complete the transaction on your GCash app and confirm here.',
        ]);
    }

    /**
     * Verify payment completion
     */
    public function verifyPayment(Request $request, GcashTransaction $transaction)
    {
        $authUser = $request->user();

        if (!$authUser || (!$authUser->isAdmin() && (int) $transaction->user_id !== (int) $authUser->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'gcash_receipt_number' => 'required|string',
        ]);

        // In production, you would validate with GCash API
        // For now, we'll mark as completed
        $transaction->update([
            'gcash_receipt_number' => $validated['gcash_receipt_number'],
            'status' => 'completed',
            'verified_at' => now(),
        ]);

        // Mark order as paid
        $order = $transaction->order;
        $order->markAsPaid($transaction);

        // Add cashback to user
        $this->addCashbackToUser($order);

        // Create delivery tracking
        $order->deliveryTracking()->create([
            'status' => 'Pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment verified successfully. Your order is confirmed.',
            'redirect' => route('customer.dashboard'),
        ]);
    }

    /**
     * Admin verify payment
     */
    public function adminVerify(Request $request, GcashTransaction $transaction)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        if ($validated['action'] === 'approve') {
            $transaction->markAsCompleted();
            
            if ($transaction->order) {
                $transaction->order->markAsPaid($transaction);
                $this->addCashbackToUser($transaction->order);
                
                if (!$transaction->order->deliveryTracking) {
                    $transaction->order->deliveryTracking()->create([
                        'status' => 'Pending',
                    ]);
                }
            }

            $message = 'Payment approved successfully.';
        } else {
            $transaction->markAsFailed();
            if ($transaction->order) {
                $transaction->order->update(['payment_status' => 'failed']);
            }
            $message = 'Payment rejected.';
        }

        if ($validated['notes']) {
            $transaction->update(['notes' => $transaction->notes . ' | Admin: ' . $validated['notes']]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Get payment history for user
     */
    public function history()
    {
        $transactions = auth()->user()->gcashTransactions()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('payment.history', compact('transactions'));
    }

    /**
     * Add cashback to user for purchase
     */
    private function addCashbackToUser(Order $order): void
    {
        $cashbackAmount = $order->calculateCashback();
        
        $cashback = $order->user->getOrCreateCashback();
        $cashback->addCashback($cashbackAmount, $order->id, 'Purchase cashback - ' . $cashbackAmount . '₱');
    }
}
