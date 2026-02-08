<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'terapis_id',
        'queue_id',
        'complaint',
        'diagnosis',
        'treatment',
        'medicine',
        'doctor_note',
        'checkup_date',
        // Field tambahan
        'anamnesis',
        'riwayat_penyakit',
        'diagnosis_awal',
        'diagnosis_akhir',
        'pengobatan',
        'obat_diberikan',
        'catatan_tambahan',
    ];

    protected $casts = [
        'checkup_date' => 'date',
    ];

    /**
     * Relasi ke Patient (User)
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Relasi ke Terapis (User)
     */
    public function terapis()
    {
        return $this->belongsTo(User::class, 'terapis_id');
    }

    /**
     * Relasi ke Patient History (Queue)
     */
    public function patientHistory()
    {
        return $this->belongsTo(PatientHistory::class, 'queue_id');
    }

    /**
     * Backward compatibility dengan Queue (jika masih digunakan)
     */
    public function queue()
    {
        return $this->belongsTo(PatientHistory::class, 'queue_id');
    }
}