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
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Patient Histories
     */
    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class, 'user_id');
    }

    /**
     * Relasi ke Medical Records sebagai Patient
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    /**
     * Relasi ke Medical Records sebagai Terapis
     */
    public function terapisMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'terapis_id');
    }

    /**
     * Check if user can manage patients (Admin or Terapis)
     */
    public function canManagePatients()
    {
        return in_array($this->role, ['admin', 'terapis']);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is terapis
     */
    public function isTerapis()
    {
        return $this->role === 'terapis';
    }

    /**
     * Check if user is regular user/patient
     */
    public function isUser()
    {
        return $this->role === 'user';
    }
}