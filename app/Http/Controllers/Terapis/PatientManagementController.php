<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\PatientHistory;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class PatientManagementController extends Controller
{
    public function dashboard()
    {
        $totalPasien = User::where('role', 'user')->count();
        $totalAntrian = PatientHistory::count();
        $antrianHariIni = PatientHistory::whereDate('created_at', today())->count();
        $antrianMenunggu = PatientHistory::where('status', 'Menunggu')->count();

        $recentQueues = PatientHistory::with('user')
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();

        return view('terapis.dashboard', compact(
            'totalPasien',
            'totalAntrian', 
            'antrianHariIni',
            'antrianMenunggu',
            'recentQueues'
        ));
    }

    public function index()
    {
        $pasiens = User::where('role', 'user')
                    ->withCount('patientHistories')
                    ->orderBy('name', 'asc')
                    ->paginate(20);

        return view('terapis.patients.index', compact('pasiens'));
    }

    public function show($id)
    {
        $pasien = User::where('role', 'user')->findOrFail($id);
        
        $riwayat = PatientHistory::where('user_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        $rekam_medis = MedicalRecord::where('patient_id', $id)
                    ->with('terapis')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('terapis.patients.show', compact('pasien', 'riwayat', 'rekam_medis'));
    }

    public function createMedicalRecord($patientId)
    {
        $pasien = User::where('role', 'user')->findOrFail($patientId);
        
        $riwayat_kunjungan = PatientHistory::where('user_id', $patientId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('terapis.medical-records.create', compact('pasien', 'riwayat_kunjungan'));
    }

    public function storeMedicalRecord(Request $request, $patientId)
    {
        // Validasi input
        $validated = $request->validate([
            'queue_id' => 'nullable|exists:patient_histories,id',
            'complaint' => 'required|string',
            'anamnesis' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
            'diagnosis' => 'required|string',
            'diagnosis_awal' => 'nullable|string',
            'diagnosis_akhir' => 'nullable|string',
            'treatment' => 'nullable|string',
            'pengobatan' => 'nullable|string',
            'medicine' => 'nullable|string',
            'obat_diberikan' => 'nullable|string',
            'doctor_note' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
            'checkup_date' => 'required|date',
        ]);

        // Tambahkan field yang wajib
        $validated['patient_id'] = $patientId;
        $validated['terapis_id'] = auth()->id();

        // Simpan ke database
        MedicalRecord::create($validated);

        return redirect()->route('terapis.patients.show', $patientId)
                        ->with('success', 'Rekam medis berhasil ditambahkan');
    }

    public function showMedicalRecord($recordId)
    {
        $record = MedicalRecord::with(['patient', 'terapis', 'patientHistory'])->findOrFail($recordId);

        // Cek akses: hanya terapis/admin atau pemilik rekam medis
        if (!auth()->user()->canManagePatients() && auth()->id() != $record->patient_id) {
            abort(403, 'Unauthorized access');
        }

        return view('terapis.medical-records.show', compact('record'));
    }
}