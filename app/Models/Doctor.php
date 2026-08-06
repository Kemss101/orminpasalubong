<?php

namespace App\Models;

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'name',
        'specialization',
        'contact',
        'status'
    ];

    public function appointments()
    {
        return $this->hasMany(AppointmentController::class);
    }

    public function consultations()
    {
        return $this->hasMany(ConsultationController::class);
    }
}