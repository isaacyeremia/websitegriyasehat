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

        // Get current queue info (antrian hari ini yang dipanggil)
        $currentQueueNumber = PatientHistory::getCurrentQueueNumber();
        $waitingCount = PatientHistory::getWaitingQueueCount();

        // Get riwayat antrian user
        $userQueues = PatientHistory::where('user_id', Auth::id())
                                   ->orderBy('created_at', 'desc')
                                   ->take(10)
                                   ->get();

        // Get antrian user hari ini yang sedang dipanggil atau menunggu
        $userTodayQueue = PatientHistory::where('user_id', Auth::id())
                                       ->whereDate('tanggal', now())
                                       ->whereIn('status', ['Menunggu', 'Dipanggil'])
                                       ->first();

        return view('antrian.index', compact(
            'services', 
            'doctors', 
            'currentQueueNumber', 
            'waitingCount', 
            'userQueues',
            'userTodayQueue'
        ));
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

        // 1. Cek apakah user sudah booking di jam yang sama
        $existingUserBooking = PatientHistory::where('user_id', Auth::id())
            ->where('tanggal', $validated['tanggal'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('dokter', $validated['dokter'])
            ->whereIn('status', ['Menunggu', 'Dipanggil'])
            ->first();

        if ($existingUserBooking) {
            return back()->withErrors([
                'appointment_time' => 'Anda sudah mendaftar di jam ' . 
                    Carbon::parse($validated['appointment_time'])->format('H:i') . 
                    ' dengan dokter ' . $validated['dokter']
            ])->withInput();
        }

        // 2. Cek apakah ada user lain yang sudah booking di slot waktu yang sama
        $existingSlotBooking = PatientHistory::where('tanggal', $validated['tanggal'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('dokter', $validated['dokter'])
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
            ->exists();

        if ($existingSlotBooking) {
            return back()->withErrors([
                'appointment_time' => 'Maaf, slot waktu ' . 
                    Carbon::parse($validated['appointment_time'])->format('H:i') . 
                    ' dengan dokter ' . $validated['dokter'] . 
                    ' sudah dibooking oleh pasien lain. Silakan pilih jam lain.'
            ])->withInput();
        }

        // 3. Cek kuota dokter
        $doctor = Doctor::where('name', $validated['dokter'])->first();
        if (!$doctor) {
            return back()->withErrors(['dokter' => 'Dokter tidak ditemukan'])->withInput();
        }

        $dayOfWeek = Carbon::parse($validated['tanggal'])->locale('id')->dayName;
        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return back()->withErrors([
                'tanggal' => 'Dokter ' . $validated['dokter'] . ' tidak praktek di hari ' . $dayOfWeek
            ])->withInput();
        }

        // Hitung booking yang sudah ada untuk tanggal tersebut
        $bookedCount = PatientHistory::where('dokter', $validated['dokter'])
            ->where('tanggal', $validated['tanggal'])
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
            ->count();

        if ($bookedCount >= $schedule->quota) {
            return back()->withErrors([
                'tanggal' => 'Kuota penuh untuk dokter ' . $validated['dokter'] . 
                    ' di tanggal ini. Silakan pilih tanggal lain atau dokter lain.'
            ])->withInput();
        }

        // Generate kode antrian
        $kodeAntrian = PatientHistory::generateQueueCode($validated['tanggal']);

        // Get user data
$user = Auth::user();

// Simpan antrian DENGAN NIK
PatientHistory::create([
    'user_id' => $user->id,
    'patient_name' => $user->name,
    'patient_nik' => $user->nik,        
    'patient_email' => $user->email,    
    'patient_phone' => $user->phone,    
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

    // API untuk get available dates berdasarkan dokter
    public function getAvailableDates($doctorId)
    {
        $doctor = Doctor::find($doctorId);
        
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Get hari praktek dokter
        $practiceDays = $doctor->schedules()
            ->where('is_active', true)
            ->get()
            ->keyBy('day_of_week');

        // Generate tanggal available untuk 30 hari ke depan
        $availableDates = [];
        $dayMap = [
            'Minggu' => 'Sunday',
            'Senin' => 'Monday',
            'Selasa' => 'Tuesday',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday',
            'Jumat' => 'Friday',
            'Sabtu' => 'Saturday',
        ];

        for ($i = 0; $i < 30; $i++) {
            $date = now()->addDays($i);
            $dayName = $date->locale('id')->dayName;
            
            if (isset($practiceDays[$dayName])) {
                $schedule = $practiceDays[$dayName];
                
                // Hitung kuota yang sudah terpakai
                $bookedCount = PatientHistory::where('dokter', $doctor->name)
                    ->where('tanggal', $date->toDateString())
                    ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
                    ->count();
                
                $quotaLeft = $schedule->quota - $bookedCount;
                
                if ($quotaLeft > 0) {
                    $availableDates[] = [
                        'date' => $date->toDateString(),
                        'display' => $date->format('d M Y') . ' (' . $dayName . ')',
                        'quota_left' => $quotaLeft,
                        'total_quota' => $schedule->quota,
                    ];
                }
            }
        }

        return response()->json($availableDates);
    }

    // API untuk get available doctors berdasarkan tanggal
    public function getAvailableDoctors($date)
    {
        try {
            $dateObj = Carbon::parse($date);
            $dayOfWeek = $dateObj->locale('id')->dayName;
            
            // Get semua dokter yang praktek di hari tersebut
            $schedules = DoctorSchedule::with('doctor')
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->get();
            
            $availableDoctors = [];
            
            foreach ($schedules as $schedule) {
                if (!$schedule->doctor || !$schedule->doctor->is_active) {
                    continue;
                }
                
                // Hitung booking yang sudah ada
                $bookedCount = PatientHistory::where('dokter', $schedule->doctor->name)
                    ->where('tanggal', $date)
                    ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
                    ->count();
                
                $quotaLeft = $schedule->quota - $bookedCount;
                
                // Hanya tampilkan dokter yang masih ada kuota
                if ($quotaLeft > 0) {
                    $availableDoctors[] = [
                        'id' => $schedule->doctor->id,
                        'name' => $schedule->doctor->name,
                        'specialization' => $schedule->doctor->specialization,
                        'start_time' => Carbon::parse($schedule->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($schedule->end_time)->format('H:i'),
                        'quota_left' => $quotaLeft,
                        'total_quota' => $schedule->quota,
                    ];
                }
            }
            
            return response()->json($availableDoctors);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // API untuk get booked slots (jam yang sudah dibooking)
    public function getBookedSlots($doctorName, $date)
    {
        $bookedSlots = PatientHistory::where('dokter', $doctorName)
            ->where('tanggal', $date)
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
            ->pluck('appointment_time')
            ->map(function($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();
        
        return response()->json($bookedSlots);
    }

    // API untuk get doctor schedule
    public function getDoctorSchedule($doctorId)
    {
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('is_active', true)
            ->get(['day_of_week', 'start_time', 'end_time', 'quota']);

        return response()->json($schedules);
    }

    public function confirmArrival($id)
    {
        $antrian = PatientHistory::findOrFail($id);
        
        if ($antrian->user_id != Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk konfirmasi antrian ini']);
        }
        
        if ($antrian->arrival_status == 'Sudah Hadir') {
            return back()->with('info', 'Anda sudah mengkonfirmasi kedatangan sebelumnya');
        }
        
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