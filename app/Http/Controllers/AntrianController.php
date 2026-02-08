<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientHistory;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Redirect to login if not authenticated
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk membuat booking antrian.');
        }

        $user = Auth::user();

        $riwayat = PatientHistory::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $antrianSekarang = PatientHistory::where('user_id', $user->id)
                    ->where('status', 'Menunggu')
                    ->orderBy('created_at', 'desc')
                    ->first();

        // Ambil daftar dokter dan layanan
        $doctors = Doctor::where('is_active', true)
                    ->orderBy('name', 'asc')
                    ->get();

        $services = Service::where('is_active', true)
                    ->orderBy('name', 'asc')
                    ->get();

        return view('antrian.index', compact('riwayat', 'antrianSekarang', 'doctors', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan' => 'required|exists:services,id',
            'dokter' => 'required|exists:doctors,id',
            'tanggal' => 'required|date',
        ]);

        $user = Auth::user();
        $tanggal = $request->tanggal;

        // Ambil nama dokter dan layanan
        $doctor = Doctor::findOrFail($request->dokter);
        $service = Service::findOrFail($request->layanan);

        // Generate kode antrian berdasarkan tanggal
        $lastQueue = PatientHistory::whereDate('tanggal', $tanggal)->count();
        $kodeAntrian = 'A' . str_pad($lastQueue + 1, 3, '0', STR_PAD_LEFT);

        PatientHistory::create([
            'user_id' => $user->id,
            'patient_name' => $user->name,
            'service' => $service->name,
            'visit_date' => $request->tanggal,
            'kode_antrian' => $kodeAntrian,
            'poli' => $service->name,
            'dokter' => $doctor->name,
            'tanggal' => $request->tanggal,
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu'
        ]);

        return redirect()->route('booking.index')
            ->with('success', 'Antrian berhasil dibuat dengan kode: ' . $kodeAntrian . ' untuk tanggal ' . Carbon::parse($tanggal)->format('d M Y'));
    }

    public function cek(Request $request)
    {
        return redirect()->back();
    }
}