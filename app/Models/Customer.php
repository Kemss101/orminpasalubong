<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'loyalty_points'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
