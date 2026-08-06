<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    //allow mass assignment form these fields
    protected $fillable = [
        'flight_id',
        'name',
        'origin',
        'destination',
    ];

    //primary key 
    protected $primaryKey = 'flight_id';

    //Disable auto-incrementing as the primary key is a string 
    public $incrementing = false;

    protected $keyTyoe = 'string';

    public $timestamps = false;

    //php artisan make:migrate create update_flight_table --table=flights
}
