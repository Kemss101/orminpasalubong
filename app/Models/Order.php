<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'delivery_address',
        'delivery_method',
        'shipping_fee',
        'payment_method',
        'gcash_transaction_id',
        'payment_status',
        'delivery_status',
        'paid_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gcashTransaction()
    {
        return $this->belongsTo(GcashTransaction::class);
    }

    public function deliveryTracking()
    {
        return $this->hasOne(DeliveryTracking::class);
    }

    // Helper methods
    public function markAsPaid(GcashTransaction $transaction): void
    {
        $this->update([
            'payment_status' => 'completed',
            'gcash_transaction_id' => $transaction->id,
            'paid_at' => now(),
        ]);
    }

    public function getPaymentStatus(): string
    {
        return $this->payment_status;
    }

    public function getDeliveryStatus(): string
    {
        return $this->delivery_status ?? 'Pending';
    }

    public function calculateCashback(): float
    {
        // 0.05% cashback rate based on items + shipping
        $grandTotal = (float) $this->total_amount + (float) ($this->shipping_fee ?? 0);

        return (float) ($grandTotal * 0.0005);
    }

    public function getGrandTotal(): float
    {
        return (float) $this->total_amount + (float) ($this->shipping_fee ?? 0);
    }
}
