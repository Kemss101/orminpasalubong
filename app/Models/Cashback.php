<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(CashbackTransaction::class, 'user_id', 'user_id');
    }

    public function addCashback(float $amount, ?int $orderId = null, ?string $reason = null): CashbackTransaction
    {
        $previousBalance = $this->balance;
        $this->increment('balance', $amount);
        $this->refresh();

        return CashbackTransaction::create([
            'user_id' => $this->user_id,
            'order_id' => $orderId,
            'type' => 'earned',
            'amount' => $amount,
            'previous_balance' => $previousBalance,
            'new_balance' => $this->balance,
            'reason' => $reason ?? 'Purchase cashback',
        ]);
    }

    public function deductCashback(float $amount, ?string $reason = null): CashbackTransaction
    {
        $previousBalance = $this->balance;
        $this->decrement('balance', $amount);
        $this->refresh();

        return CashbackTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'spent',
            'amount' => $amount,
            'previous_balance' => $previousBalance,
            'new_balance' => $this->balance,
            'reason' => $reason ?? 'Cashback redeemed',
        ]);
    }

    public function canRedeemAmount(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}
