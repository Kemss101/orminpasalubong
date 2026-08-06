<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GcashTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'reference_number',
        'amount',
        'status',
        'type',
        'gcash_receipt_number',
        'verified_at',
        'notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'verified_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    public function refund(): void
    {
        $this->update(['status' => 'refunded']);
    }

    public function getGcashUrl(): string
    {
        return 'gcash://pay?reference=' . urlencode($this->reference_number) . '&amount=' . urlencode(number_format((float) $this->amount, 2, '.', ''));
    }
}
