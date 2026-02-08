<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    protected $fillable = [
        'user_id',
        'patient_name',
        'service',
        'visit_date',
        'kode_antrian',
        'poli',
        'dokter',
        'tanggal',
        'keluhan',
        'status'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}