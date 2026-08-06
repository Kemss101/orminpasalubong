<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryTracking extends Model
{
    protected $table = 'delivery_tracking';

    protected $fillable = [
        'order_id',
        'status',
        'tracking_number',
        'shipped_at',
        'out_for_delivery_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'out_for_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function updateStatus(string $newStatus): void
    {
        $this->status = $newStatus;

        match ($newStatus) {
            'Shipped' => $this->shipped_at = now(),
            'Out for Delivery' => $this->out_for_delivery_at = now(),
            'Delivered' => $this->delivered_at = now(),
            default => null,
        };

        $this->save();
    }

    public function isDelivered(): bool
    {
        return $this->status === 'Delivered';
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }
}
