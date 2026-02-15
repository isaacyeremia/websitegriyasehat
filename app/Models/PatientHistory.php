<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatientHistory extends Model
{
    protected $fillable = [
    'user_id',
    'patient_name',
    'patient_nik',  // ✅ Sudah ada
    'patient_email',
    'patient_phone',
    'kode_antrian',
    'poli',
    'dokter',
    'tanggal',
    'appointment_time',
    'keluhan',
    'status',
    'arrival_status',
    'confirmed_at',
];

    /**
     * PENTING: Cast untuk timezone yang benar
     */
    protected $casts = [
        'tanggal' => 'date',
        'appointment_time' => 'datetime:H:i', // Format jam saja
        'confirmed_at' => 'datetime', // Akan otomatis pakai timezone dari config
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Format confirmed_at ke WIB
     */
    public function getConfirmedAtFormattedAttribute()
    {
        if (!$this->confirmed_at) {
            return null;
        }
        
        return Carbon::parse($this->confirmed_at)
                    ->timezone('Asia/Jakarta')
                    ->format('d M Y H:i');
    }

    // Helper: Get antrian yang sedang dipanggil hari ini
    public static function getCurrentQueueNumber($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return self::where('tanggal', $date)
                   ->where('status', 'Dipanggil')
                   ->orderBy('created_at', 'asc')
                   ->value('kode_antrian');
    }

    // Helper: Get total antrian menunggu hari ini
    public static function getWaitingQueueCount($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return self::where('tanggal', $date)
                   ->where('status', 'Menunggu')
                   ->count();
    }

    // Helper: Cek kuota tersedia untuk dokter di tanggal tertentu
    public static function isQuotaAvailable($doctorName, $date)
    {
        $doctor = \App\Models\Doctor::where('name', $doctorName)->first();
        
        if (!$doctor) {
            return false;
        }

        // Get jadwal dokter untuk hari tersebut
        $dayOfWeek = Carbon::parse($date)->locale('id')->dayName;
        $dayMap = [
            'Senin' => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu' => 'Rabu',
            'Kamis' => 'Kamis',
            'Jumat' => 'Jumat',
            'Sabtu' => 'Sabtu',
            'Minggu' => 'Minggu',
        ];
        
        $schedule = $doctor->schedules()
                          ->where('day_of_week', $dayMap[$dayOfWeek])
                          ->where('is_active', true)
                          ->first();

        if (!$schedule) {
            return false;
        }

        // Hitung antrian yang sudah ada
        $bookedCount = self::where('dokter', $doctorName)
                          ->where('tanggal', $date)
                          ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
                          ->count();

        return $bookedCount < $schedule->quota;
    }

    // Helper: Generate kode antrian
    public static function generateQueueCode($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $lastQueue = self::where('tanggal', $date)
                        ->orderBy('id', 'desc')
                        ->first();

        if ($lastQueue) {
            $lastNumber = (int) substr($lastQueue->kode_antrian, 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'A' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}