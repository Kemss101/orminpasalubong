<?php

namespace App\Http\Controllers;

use App\Models\Cashback;
use Illuminate\Http\Request;

class CashbackController extends Controller
{
    /**
     * Get user's cashback balance
     */
    public function getBalance()
    {
        $user = auth()->user();
        $cashback = $user->getOrCreateCashback();

        return response()->json([
            'balance' => (float)$cashback->balance,
            'formatted_balance' => '₱' . number_format($cashback->balance, 2),
        ]);
    }

    /**
     * Show cashback dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        $cashback = $user->getOrCreateCashback();
        $transactions = $user->cashbackTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_earned' => $user->cashbackTransactions()->where('type', 'earned')->sum('amount'),
            'total_spent' => $user->cashbackTransactions()->where('type', 'spent')->sum('amount'),
            'current_balance' => (float)$cashback->balance,
        ];

        return view('cashback.dashboard', compact('cashback', 'transactions', 'stats'));
    }

    /**
     * Get cashback history
     */
    public function history(Request $request)
    {
        $query = auth()->user()->cashbackTransactions();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($transactions);
    }

    /**
     * Redeem cashback (convert to discount)
     */
    public function redeem(Request $request)
    {
        $user = auth()->user();
        $cashback = $user->getOrCreateCashback();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        if (!$cashback->canRedeemAmount($validated['amount'])) {
            return response()->json([
                'error' => 'Insufficient cashback balance',
            ], 400);
        }

        $cashback->deductCashback($validated['amount'], 'Redeemed for discount');

        return response()->json([
            'status' => 'success',
            'message' => 'Cashback redeemed successfully',
            'new_balance' => (float)$cashback->balance,
            'redeemed_amount' => $validated['amount'],
        ]);
    }

    /**
     * Admin view all user cashback
     */
    public function adminView(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $query = Cashback::with('user')->orderBy('balance', 'desc');

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $cashbacks = $query->paginate(20);

        return view('admin.cashback-monitoring', compact('cashbacks'));
    }

    /**
     * Admin adjust user cashback
     */
    public function adminAdjust(Request $request, Cashback $cashback)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:add,deduct',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
        ]);

        if ($validated['action'] === 'add') {
            $cashback->addCashback($validated['amount'], null, '[Admin] ' . $validated['reason']);
            $message = 'Cashback added successfully';
        } else {
            if (!$cashback->canRedeemAmount($validated['amount'])) {
                return response()->json(['error' => 'Insufficient cashback balance'], 400);
            }
            $cashback->deductCashback($validated['amount'], '[Admin] ' . $validated['reason']);
            $message = 'Cashback deducted successfully';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'new_balance' => (float)$cashback->balance,
        ]);
    }
}
