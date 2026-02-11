<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'quota',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Helper: Get jadwal aktif untuk hari tertentu
    public static function getActiveSchedulesByDay($dayOfWeek)
    {
        return self::with('doctor')
                   ->where('day_of_week', $dayOfWeek)
                   ->where('is_active', true)
                   ->get();
    }

    // Helper: Cek apakah dokter praktek di hari tertentu
    public static function isDoctorAvailable($doctorId, $dayOfWeek)
    {
        return self::where('doctor_id', $doctorId)
                   ->where('day_of_week', $dayOfWeek)
                   ->where('is_active', true)
                   ->exists();
    }
}