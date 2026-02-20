<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'schedule',
        'image',
        'is_active',
        'daftar_harga',      // TAMBAH
        'urutan',            // TAMBAH
        'show_in_about',     // TAMBAH
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'show_in_about' => 'boolean',  // TAMBAH
        'daftar_harga'  => 'array',    // TAMBAH
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

    // ===== SCOPE BARU UNTUK ABOUT PAGE =====
    
    // Scope untuk yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk yang tampil di About
    public function scopeShowInAbout($query)
    {
        return $query->where('show_in_about', true);
    }

    // Scope sorting berdasarkan urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('name', 'asc');
    }
}