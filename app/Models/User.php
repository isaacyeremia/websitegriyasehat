<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'phone',
    'nik',  // ✅ Tambahkan jika belum ada
    'address',
    'email',
    'password',
    'role',
    'reset_token',
    'reset_token_expires_at',
];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'reset_token_expires_at' => 'datetime',
    ];

    // Role methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTerapis()
    {
        return $this->role === 'terapis';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function canManagePatients()
    {
        return $this->role === 'admin' || $this->role === 'terapis';
    }

    // Relationships
    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    public function medicalRecordsAsTerapis()
    {
        return $this->hasMany(MedicalRecord::class, 'terapis_id');
    }
}