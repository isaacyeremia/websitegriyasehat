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

        $services = Service::where('is_active', true)->get();
        $doctors  = Doctor::where('is_active', true)->with('schedules')->get();

        $currentQueueNumber = PatientHistory::getCurrentQueueNumber();
        $waitingCount       = PatientHistory::getWaitingQueueCount();

        $userQueues = PatientHistory::where('user_id', Auth::id())
                                   ->orderBy('created_at', 'desc')
                                   ->take(10)
                                   ->get();

        $userTodayQueue = PatientHistory::where('user_id', Auth::id())
                                       ->whereDate('tanggal', now())
                                       ->whereIn('status', ['Menunggu', 'Dipanggil'])
                                       ->first();

        return view('antrian.index', compact(
            'services', 'doctors', 'currentQueueNumber',
            'waitingCount', 'userQueues', 'userTodayQueue'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'poli'             => 'required|string',
            'dokter'           => 'required|string',
            'tanggal'          => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'keluhan'          => 'nullable|string',
        ]);

        // Ambil durasi layanan, fallback 20 menit
        $service     = Service::where('name', $validated['poli'])->first();
        $durasiMenit = $service ? $service->duration_minutes : 20;

        // VALIDASI 1: Jeda antar booking berdasarkan durasi layanan
        $jamBooking = Carbon::createFromFormat('H:i', $validated['appointment_time']);
        $jamMin     = $jamBooking->copy()->subMinutes($durasiMenit)->format('H:i:s');
        $jamMax     = $jamBooking->copy()->addMinutes($durasiMenit)->format('H:i:s');

        $bentrokJeda = PatientHistory::where('tanggal', $validated['tanggal'])
            ->where('dokter', $validated['dokter'])
            ->whereNotIn('status', ['Dibatalkan'])
            ->whereRaw("appointment_time > ? AND appointment_time < ?", [$jamMin, $jamMax])
            ->exists();

        if ($bentrokJeda) {
            return back()->withErrors([
                'appointment_time' => 'Jam ini terlalu berdekatan. Layanan "' . $validated['poli'] .
                    '" membutuhkan jeda minimal ' . $durasiMenit . ' menit.'
            ])->withInput();
        }

        // VALIDASI 2: Duplikat booking (user + dokter + poli + tanggal sama)
        $bookingDuplikat = PatientHistory::where('user_id', Auth::id())
            ->where('tanggal', $validated['tanggal'])
            ->where('dokter', $validated['dokter'])
            ->where('poli', $validated['poli'])
            ->whereNotIn('status', ['Dibatalkan'])
            ->exists();

        if ($bookingDuplikat) {
            return back()->withErrors([
                'poli' => 'Anda sudah punya booking dengan terapis dan layanan yang sama di tanggal ini.'
            ])->withInput();
        }

        // VALIDASI 3: Slot waktu persis sudah dibooking
        $existingSlotBooking = PatientHistory::where('tanggal', $validated['tanggal'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('dokter', $validated['dokter'])
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
            ->exists();

        if ($existingSlotBooking) {
            return back()->withErrors([
                'appointment_time' => 'Slot waktu ' .
                    Carbon::parse($validated['appointment_time'])->format('H:i') .
                    ' sudah dibooking pasien lain. Silakan pilih jam lain.'
            ])->withInput();
        }

        // VALIDASI 4: Kuota dokter
        $doctor = Doctor::where('name', $validated['dokter'])->first();
        if (!$doctor) {
            return back()->withErrors(['dokter' => 'Dokter tidak ditemukan'])->withInput();
        }

        $dayOfWeek = Carbon::parse($validated['tanggal'])->locale('id')->dayName;
        $schedule  = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return back()->withErrors([
                'tanggal' => 'Dokter ' . $validated['dokter'] . ' tidak praktek di hari ' . $dayOfWeek
            ])->withInput();
        }

        $bookedCount = PatientHistory::where('dokter', $validated['dokter'])
            ->where('tanggal', $validated['tanggal'])
            ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
            ->count();

        if ($bookedCount >= $schedule->quota) {
            return back()->withErrors([
                'tanggal' => 'Kuota penuh untuk dokter ' . $validated['dokter'] . ' di tanggal ini.'
            ])->withInput();
        }

        // SIMPAN dengan kode sementara
        $user   = Auth::user();
        $prefix = 'GS-' . Carbon::parse($validated['tanggal'])->format('dmy');

        $history = PatientHistory::create([
            'user_id'          => $user->id,
            'patient_name'     => $user->name,
            'patient_nik'      => $user->nik,
            'patient_email'    => $user->email,
            'patient_phone'    => $user->phone,
            'kode_antrian'     => 'TEMP-' . time(),
            'poli'             => $validated['poli'],
            'dokter'           => $validated['dokter'],
            'tanggal'          => $validated['tanggal'],
            'appointment_time' => $validated['appointment_time'],
            'keluhan'          => $validated['keluhan'],
            'status'           => 'Menunggu',
            'arrival_status'   => 'Belum Hadir',
        ]);

        // RE-ASSIGN kode antrian berdasarkan urutan appointment_time
        $semuaBooking = PatientHistory::where('tanggal', $validated['tanggal'])
            ->where('dokter', $validated['dokter'])
            ->whereNotIn('status', ['Dibatalkan'])
            ->orderBy('appointment_time', 'asc')
            ->get();
        
        foreach ($semuaBooking as $i => $booking) {
            $booking->update([
                'kode_antrian' => 'A' . str_pad($i + 1, 3, '0', STR_PAD_LEFT)
            ]);
        }

        $history->refresh();

        return redirect()->route('booking.index')
            ->with('success', 'Booking berhasil! Kode antrian: ' . $history->kode_antrian .
                ' | Layanan: ' . $validated['poli'] . ' (' . $durasiMenit . ' menit)');
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

    public function getAvailableDoctors($date)
    {
        try {
            $dateObj   = Carbon::parse($date);
            $dayOfWeek = $dateObj->locale('id')->dayName;

            $schedules = DoctorSchedule::with('doctor')
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->get();

            $availableDoctors = [];

            foreach ($schedules as $schedule) {
                if (!$schedule->doctor || !$schedule->doctor->is_active) continue;

                $bookedCount = PatientHistory::where('dokter', $schedule->doctor->name)
                    ->where('tanggal', $date)
                    ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
                    ->count();

                $quotaLeft = $schedule->quota - $bookedCount;

                if ($quotaLeft > 0) {
                    $availableDoctors[] = [
                        'id'          => $schedule->doctor->id,
                        'name'        => $schedule->doctor->name,
                        'start_time'  => Carbon::parse($schedule->start_time)->format('H:i'),
                        'end_time'    => Carbon::parse($schedule->end_time)->format('H:i'),
                        'quota_left'  => $quotaLeft,
                        'total_quota' => $schedule->quota,
                    ];
                }
            }

            return response()->json($availableDoctors);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getAvailableDates($doctorId)
    {
        $doctor = Doctor::find($doctorId);
        if (!$doctor) return response()->json(['error' => 'Doctor not found'], 404);

        $practiceDays   = $doctor->schedules()->where('is_active', true)->get()->keyBy('day_of_week');
        $availableDates = [];

        for ($i = 0; $i < 30; $i++) {
            $date    = now()->addDays($i);
            $dayName = $date->locale('id')->dayName;

            if (isset($practiceDays[$dayName])) {
                $schedule    = $practiceDays[$dayName];
                $bookedCount = PatientHistory::where('dokter', $doctor->name)
                    ->where('tanggal', $date->toDateString())
                    ->whereIn('status', ['Menunggu', 'Dipanggil', 'Selesai'])
                    ->count();

                $quotaLeft = $schedule->quota - $bookedCount;

                if ($quotaLeft > 0) {
                    $availableDates[] = [
                        'date'        => $date->toDateString(),
                        'display'     => $date->format('d M Y') . ' (' . $dayName . ')',
                        'quota_left'  => $quotaLeft,
                        'total_quota' => $schedule->quota,
                    ];
                }
            }
        }

        return response()->json($availableDates);
    }

    // API booked slots + blocked berdasarkan durasi masing-masing layanan
    public function getBookedSlots($doctorName, $date)
    {
        $bookings = PatientHistory::where('dokter', $doctorName)
            ->where('tanggal', $date)
            ->whereNotIn('status', ['Dibatalkan'])
            ->select('appointment_time', 'poli')
            ->get();

        $bookedTimes  = [];
        $blockedSlots = [];

        foreach ($bookings as $booking) {
            $jam           = Carbon::parse($booking->appointment_time)->format('H:i');
            $bookedTimes[] = $jam;

            $service   = Service::where('name', $booking->poli)->first();
            $durasi    = $service ? $service->duration_minutes : 20;
            $jamCarbon = Carbon::createFromFormat('H:i', $jam);

            for ($m = -($durasi - 1); $m <= ($durasi - 1); $m++) {
                if ($m === 0) continue;
                $blockedSlots[] = $jamCarbon->copy()->addMinutes($m)->format('H:i');
            }
        }

        return response()->json([
            'booked'  => $bookedTimes,
            'blocked' => array_values(array_unique($blockedSlots)),
        ]);
    }

    // API durasi layanan untuk validasi di frontend
    public function getServiceDurations()
    {
        $services = Service::where('is_active', true)
            ->select('name', 'duration_minutes')
            ->get()
            ->keyBy('name')
            ->map(fn($s) => $s->duration_minutes);

        return response()->json($services);
    }

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
            return back()->withErrors(['error' => 'Tidak memiliki akses']);
        }

        if ($antrian->arrival_status == 'Sudah Hadir') {
            return back()->with('info', 'Anda sudah mengkonfirmasi kedatangan sebelumnya');
        }

        $antrian->arrival_status = 'Sudah Hadir';
        $antrian->confirmed_at   = now();
        $antrian->save();

        return back()->with('success', 'Konfirmasi kedatangan berhasil!');
    }

    public function cancelArrival($id)
    {
        $antrian = PatientHistory::findOrFail($id);

        if ($antrian->user_id != Auth::id()) {
            return back()->withErrors(['error' => 'Tidak memiliki akses']);
        }

        $antrian->arrival_status = 'Belum Hadir';
        $antrian->confirmed_at   = null;
        $antrian->save();

        return back()->with('success', 'Konfirmasi kedatangan dibatalkan');
    }
}
