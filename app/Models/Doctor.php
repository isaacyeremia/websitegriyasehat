<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialization',
        'schedule',
        'image',
        'is_active'
    ];

    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class);
    }
}