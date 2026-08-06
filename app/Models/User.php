<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'address',
        'password',
        'user_type',
        'google_id',
        'profile_picture',
        'auth_provider',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function hasUserType(string $type): bool
    {
        return $this->user_type === $type;
    }

    public function isAdmin(): bool
    {
        return $this->hasUserType('admin');
    }

    public function isSeller(): bool
    {
        return $this->hasUserType('seller');
    }

    public function isCustomer(): bool
    {
        return $this->hasUserType('customer');
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function gcashTransactions()
    {
        return $this->hasMany(GcashTransaction::class);
    }

    public function cashback()
    {
        return $this->hasOne(Cashback::class);
    }

    public function cashbackTransactions()
    {
        return $this->hasMany(CashbackTransaction::class);
    }

    public function deliveryTrackings()
    {
        return $this->hasManyThrough(DeliveryTracking::class, Order::class);
    }

    // Helper methods
    public function getCashbackBalance(): float
    {
        $cashback = $this->cashback;
        return $cashback ? (float)$cashback->balance : 0;
    }

    public function getOrCreateCashback(): Cashback
    {
        return $this->cashback ?? Cashback::create(['user_id' => $this->id, 'balance' => 0]);
    }
}