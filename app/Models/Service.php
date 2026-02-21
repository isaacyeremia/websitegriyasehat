<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'is_active',
        'duration_minutes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke patient histories
    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class, 'poli', 'name');
    }

    // Get active services
    public static function getActiveServices()
    {
        return self::where('is_active', true)->get();
    }
}