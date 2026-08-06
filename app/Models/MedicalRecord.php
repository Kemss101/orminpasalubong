<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'patient_id',
        'details'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}