<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientHistory;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk booking antrian');
        }

        // Get daftar poli/layanan
        $services = Service::where('is_active', true)->get();
        
        // Get daftar dokter aktif dengan jadwal
        $doctors = Doctor::where('is_active', true)
                        ->with('schedules')
                        ->get();

        // Get current queue info (antrian hari ini)
        $currentQueueNumber = PatientHistory::getCurrentQueueNumber();
        $waitingCount = PatientHistory::getWaitingQueueCount();

        // Get riwayat antrian user
        $userQueues = PatientHistory::where('user_id', Auth::id())
                                   ->orderBy('created_at', 'desc')
                                   ->take(10)
                                   ->get();

        return view('antrian.index', compact('services', 'doctors', 'currentQueueNumber', 'waitingCount', 'userQueues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'poli' => 'required|string',
            'dokter' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'keluhan' => 'nullable|string',
        ]);

        // Cek kuota
        if (!PatientHistory::isQuotaAvailable($validated['dokter'], $validated['tanggal'])) {
            return back()->withErrors(['tanggal' => 'Kuota untuk dokter ' . $validated['dokter'] . ' di tanggal ini sudah penuh. Silakan pilih tanggal lain.']);
        }

        // Generate kode antrian
        $kodeAntrian = PatientHistory::generateQueueCode($validated['tanggal']);

        // Simpan antrian
        PatientHistory::create([
            'user_id' => Auth::id(),
            'patient_name' => Auth::user()->name,
            'kode_antrian' => $kodeAntrian,
            'poli' => $validated['poli'],
            'dokter' => $validated['dokter'],
            'tanggal' => $validated['tanggal'],
            'appointment_time' => $validated['appointment_time'],
            'keluhan' => $validated['keluhan'],
            'status' => 'Menunggu',
            'arrival_status' => 'Belum Hadir',
        ]);

        return redirect()->route('booking.index')
                        ->with('success', 'Booking antrian berhasil! Kode antrian Anda: ' . $kodeAntrian);
    }

    public function cek(Request $request)
    {
        $validated = $request->validate([
            'kode_antrian' => 'required|string',
        ]);

        $antrian = PatientHistory::where('kode_antrian', $validated['kode_antrian'])->first();

        if (!$antrian) {
            return back()->withErrors(['kode_antrian' => 'Kode antrian tidak ditemukan']);
        }

        return back()->with('queue_info', $antrian);
    }

    // API untuk get jadwal dokter (AJAX)
    public function getDoctorSchedule($doctorId)
    {
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
                                   ->where('is_active', true)
                                   ->get(['day_of_week', 'start_time', 'end_time', 'quota']);

        return response()->json($schedules);
    }

    // API untuk get available dates (AJAX)
    public function getAvailableDates($doctorId)
    {
        $doctor = Doctor::find($doctorId);
        
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Get hari praktek dokter
        $practiceDays = $doctor->schedules()
                              ->where('is_active', true)
                              ->pluck('day_of_week')
                              ->toArray();

        // Generate tanggal available untuk 30 hari ke depan
        $availableDates = [];
        $dayMap = [
            'Senin' => 'Monday',
            'Selasa' => 'Tuesday',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday',
            'Jumat' => 'Friday',
            'Sabtu' => 'Saturday',
            'Minggu' => 'Sunday',
        ];

        for ($i = 0; $i < 30; $i++) {
            $date = now()->addDays($i);
            $dayName = $date->locale('id')->dayName;
            
            if (in_array($dayName, $practiceDays)) {
                // Cek kuota
                $isAvailable = PatientHistory::isQuotaAvailable($doctor->name, $date->toDateString());
                
                if ($isAvailable) {
                    $availableDates[] = [
                        'date' => $date->toDateString(),
                        'display' => $date->format('d M Y') . ' (' . $dayName . ')',
                    ];
                }
            }
        }

        return response()->json($availableDates);
    }

    public function confirmArrival($id)
{
    $antrian = PatientHistory::findOrFail($id);
    
    // Cek apakah antrian milik user yang login
    if ($antrian->user_id != Auth::id()) {
        return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk konfirmasi antrian ini']);
    }
    
    // Cek apakah sudah pernah konfirmasi
    if ($antrian->arrival_status == 'Sudah Hadir') {
        return back()->with('info', 'Anda sudah mengkonfirmasi kedatangan sebelumnya');
    }
    
    // Update status kedatangan
    $antrian->arrival_status = 'Sudah Hadir';
    $antrian->confirmed_at = now();
    $antrian->save();
    
    return back()->with('success', 'Konfirmasi kedatangan berhasil! Silakan menunggu giliran Anda dipanggil.');
}

public function cancelArrival($id)
{
    $antrian = PatientHistory::findOrFail($id);
    
    if ($antrian->user_id != Auth::id()) {
        return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk membatalkan konfirmasi ini']);
    }
    
    $antrian->arrival_status = 'Belum Hadir';
    $antrian->confirmed_at = null;
    $antrian->save();
    
    return back()->with('success', 'Konfirmasi kedatangan dibatalkan');
}
}