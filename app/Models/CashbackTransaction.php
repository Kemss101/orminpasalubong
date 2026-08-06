<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashbackTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'amount',
        'previous_balance',
        'new_balance',
        'reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
