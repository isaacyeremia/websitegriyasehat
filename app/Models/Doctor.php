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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke jadwal praktek
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    // Relasi ke patient histories
    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class, 'dokter', 'name');
    }

    // Helper: Get jadwal dokter yang aktif
    public function getActiveSchedules()
    {
        return $this->schedules()->where('is_active', true)->get();
    }

    // Helper: Get hari praktek dokter
    public function getPracticeDays()
    {
        return $this->schedules()
                    ->where('is_active', true)
                    ->pluck('day_of_week')
                    ->toArray();
    }
}