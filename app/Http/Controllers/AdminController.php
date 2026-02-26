<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientHistory;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $statistik = [
            'total'        => PatientHistory::count(),
            'menunggu'     => PatientHistory::where('status', 'Menunggu')->count(),
            'dipanggil'    => PatientHistory::where('status', 'Dipanggil')->count(),
            'selesai'      => PatientHistory::where('status', 'Selesai')->count(),
            'dibatalkan'   => PatientHistory::where('status', 'Dibatalkan')->count(),
            'total_pasien' => User::where('role', 'user')->count(),
        ];

        $query = PatientHistory::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('arrival_status')) {
            $query->where('arrival_status', $request->arrival_status);
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }

        $antrians = $query->orderBy('tanggal', 'asc')
                          ->orderBy('appointment_time', 'asc')
                          ->paginate(20);

        return view('admin.dashboard', compact('statistik', 'antrians'));
    }

    /**
     * API endpoint untuk AJAX polling live data dashboard (setiap 5 detik).
     */
    public function liveData(Request $request)
    {
        $statistik = [
            'total'        => PatientHistory::count(),
            'menunggu'     => PatientHistory::where('status', 'Menunggu')->count(),
            'dipanggil'    => PatientHistory::where('status', 'Dipanggil')->count(),
            'selesai'      => PatientHistory::where('status', 'Selesai')->count(),
            'dibatalkan'   => PatientHistory::where('status', 'Dibatalkan')->count(),
            'total_pasien' => User::where('role', 'user')->count(),
        ];

        $query = PatientHistory::query();

        if ($request->filled('status'))         { $query->where('status', $request->status); }
        if ($request->filled('arrival_status')) { $query->where('arrival_status', $request->arrival_status); }
        if ($request->filled('date'))           { $query->whereDate('tanggal', $request->date); }

        $antrians = $query->orderBy('tanggal', 'asc')
                          ->orderBy('appointment_time', 'asc')
                          ->paginate(20)
                          ->through(function ($a) {
                              return [
                                  'id'               => $a->id,
                                  'kode_antrian'     => $a->kode_antrian,
                                  'patient_name'     => $a->patient_name,
                                  'patient_nik'      => $a->patient_nik ?? '-',
                                  'poli'             => $a->poli,
                                  'dokter'           => $a->dokter,
                                  'tanggal'          => \Carbon\Carbon::parse($a->tanggal)->format('d M Y'),
                                  'tanggal_raw'      => $a->tanggal,
                                  'appointment_time' => $a->appointment_time
                                      ? \Carbon\Carbon::parse($a->appointment_time)->format('H:i') : null,
                                  'keluhan'          => $a->keluhan
                                      ? \Illuminate\Support\Str::limit($a->keluhan, 30) : '-',
                                  'status'           => $a->status,
                                  'arrival_status'   => $a->arrival_status,
                                  'confirmed_at'     => $a->confirmed_at
                                      ? \Carbon\Carbon::parse($a->confirmed_at)->timezone('Asia/Jakarta')->format('H:i') : null,
                              ];
                          });

        return response()->json([
            'statistik' => $statistik,
            'antrians'  => $antrians,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status'         => 'required|in:Menunggu,Dipanggil,Selesai,Dibatalkan',
            'arrival_status' => 'nullable|in:Belum Hadir,Sudah Hadir,Tidak Hadir',
        ]);

        $antrian = PatientHistory::findOrFail($id);
        $antrian->status = $validated['status'];

        if (isset($validated['arrival_status'])) {
            $antrian->arrival_status = $validated['arrival_status'];

            if ($validated['arrival_status'] == 'Sudah Hadir') {
                $antrian->confirmed_at = \Carbon\Carbon::now('Asia/Jakarta');
            } else {
                $antrian->confirmed_at = null;
            }
        }

        $antrian->save();

        // =====================================================
        // Jika status → Selesai / Dibatalkan
        // atau arrival → Tidak Hadir
        // → re-assign nomor antrian supaya slot terbuka
        //   dan nomor urut tetap rapi tanpa gap
        // =====================================================
        $perluReassign =
            in_array($validated['status'], ['Selesai', 'Dibatalkan']) ||
            (isset($validated['arrival_status']) && $validated['arrival_status'] === 'Tidak Hadir');

        if ($perluReassign) {
            AntrianController::reassignQueueCodes($antrian->tanggal, $antrian->dokter);
        }

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Status antrian ' . $antrian->kode_antrian . ' berhasil diupdate');
    }

    public function destroy($id)
    {
        $antrian = PatientHistory::findOrFail($id);
        $tanggal = $antrian->tanggal;
        $dokter  = $antrian->dokter;
        $kode    = $antrian->kode_antrian;

        $antrian->delete();

        // Re-assign setelah hapus agar tidak ada gap nomor
        AntrianController::reassignQueueCodes($tanggal, $dokter);

        return redirect()->back()->with('success', 'Antrian ' . $kode . ' berhasil dihapus');
    }

    // ===== MANAJEMEN PASIEN =====

    public function patients()
    {
        $pasiens = User::where('role', 'user')
                    ->withCount(['patientHistories', 'medicalRecords'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('admin.patients.index', compact('pasiens'));
    }

    public function showPatient($id)
    {
        $pasien = User::where('role', 'user')->findOrFail($id);

        $riwayat = PatientHistory::where('user_id', $id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('appointment_time', 'asc')
                    ->get();

        $rekam_medis = MedicalRecord::where('patient_id', $id)
                    ->with('terapis')
                    ->orderBy('checkup_date', 'desc')
                    ->get();

        return view('admin.patients.show', compact('pasien', 'riwayat', 'rekam_medis'));
    }

    public function editPatient($id)
    {
        $pasien = User::where('role', 'user')->findOrFail($id);
        return view('admin.patients.edit', compact('pasien'));
    }

    public function updatePatient(Request $request, $id)
    {
        $pasien = User::where('role', 'user')->findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'nik'     => 'nullable|string|size:16|unique:users,nik,' . $id,
            'phone'   => 'required|string|unique:users,phone,' . $id,
            'email'   => 'required|email|unique:users,email,' . $id,
            'address' => 'nullable|string',
        ], [
            'nik.size'   => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
        ]);

        $pasien->update($validated);

        return redirect()->route('admin.patients.show', $id)
                        ->with('success', 'Data pasien berhasil diupdate');
    }

    public function destroyPatient($id)
    {
        $pasien = User::where('role', 'user')->findOrFail($id);
        $name   = $pasien->name;

        $pasien->patientHistories()->delete();
        $pasien->medicalRecords()->delete();
        $pasien->delete();

        return redirect()->route('admin.patients.index')
                        ->with('success', 'Data pasien ' . $name . ' dan semua riwayatnya berhasil dihapus');
    }

    // ===== MANAJEMEN TERAPIS =====

    public function terapisIndex()
    {
        $terapis = User::where('role', 'terapis')
                    ->withCount('medicalRecordsAsTerapis')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('admin.terapis.index', compact('terapis'));
    }

    public function createTerapis()
    {
        return view('admin.terapis.create');
    }

    public function storeTerapis(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'email'    => 'required|email|unique:users,email',
            'address'  => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'phone'    => $validated['phone'],
            'email'    => $validated['email'],
            'address'  => $validated['address'],
            'password' => Hash::make($validated['password']),
            'role'     => 'terapis',
        ]);

        return redirect()->route('admin.terapis.index')
                        ->with('success', 'Akun terapis berhasil dibuat! Username: ' . $validated['phone']);
    }

    public function editTerapis($id)
    {
        $terapis = User::where('role', 'terapis')->findOrFail($id);
        return view('admin.terapis.edit', compact('terapis'));
    }

    public function updateTerapis(Request $request, $id)
    {
        $terapis = User::where('role', 'terapis')->findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone,' . $id,
            'email'    => 'required|email|unique:users,email,' . $id,
            'address'  => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $terapis->update([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'email'   => $validated['email'],
            'address' => $validated['address'],
        ]);

        if ($request->filled('password')) {
            $terapis->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.terapis.index')
                        ->with('success', 'Data terapis berhasil diupdate');
    }

    public function destroyTerapis($id)
    {
        $terapis = User::where('role', 'terapis')->findOrFail($id);
        $name    = $terapis->name;

        $terapis->medicalRecordsAsTerapis()->delete();
        $terapis->delete();

        return redirect()->route('admin.terapis.index')
                        ->with('success', 'Akun terapis ' . $name . ' berhasil dihapus');
    }

    // ===== MANAJEMEN REKAM MEDIS =====

    public function showMedicalRecord($recordId)
    {
        $record = MedicalRecord::with(['patient', 'terapis', 'patientHistory'])->findOrFail($recordId);
        return view('admin.medical-records.show', compact('record'));
    }

    public function createMedicalRecord($patientId)
    {
        $pasien = User::where('role', 'user')->findOrFail($patientId);

        $riwayat_kunjungan = PatientHistory::where('user_id', $patientId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('admin.medical-records.create', compact('pasien', 'riwayat_kunjungan'));
    }

    public function storeMedicalRecord(Request $request, $patientId)
    {
        $validated = $request->validate([
            'queue_id'         => 'nullable|exists:patient_histories,id',
            'complaint'        => 'required|string',
            'anamnesis'        => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
            'diagnosis'        => 'required|string',
            'diagnosis_awal'   => 'nullable|string',
            'diagnosis_akhir'  => 'nullable|string',
            'treatment'        => 'nullable|string',
            'pengobatan'       => 'nullable|string',
            'medicine'         => 'nullable|string',
            'obat_diberikan'   => 'nullable|string',
            'doctor_note'      => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
            'checkup_date'     => 'required|date',
        ]);

        MedicalRecord::create(array_merge($validated, [
            'patient_id' => $patientId,
            'terapis_id' => auth()->id(),
        ]));

        return redirect()->route('admin.patients.show', $patientId)
                        ->with('success', 'Rekam medis berhasil ditambahkan');
    }

    public function allMedicalRecords(Request $request)
    {
        $query = MedicalRecord::with(['patient', 'terapis']);

        if ($request->filled('terapis_id')) {
            $query->where('terapis_id', $request->terapis_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->where('checkup_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('checkup_date', '<=', $request->date_to);
        }

        $records    = $query->orderBy('checkup_date', 'desc')->paginate(20);
        $allTerapis = User::where('role', 'terapis')
                          ->orWhere('role', 'admin')
                          ->orderBy('name')
                          ->get();

        return view('admin.medical-records.all', compact('records', 'allTerapis'));
    }
}