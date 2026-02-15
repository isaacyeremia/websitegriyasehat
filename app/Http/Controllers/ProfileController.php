<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientHistory;
use App\Models\MedicalRecord;

class ProfileController extends Controller
{
    /**
     * Halaman profile user
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil riwayat pasien HANYA milik user ini
        $histories = PatientHistory::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Ambil rekam medis HANYA milik user ini
        $rekam_medis = MedicalRecord::where('patient_id', $user->id)
            ->with('terapis')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.index', compact('user', 'histories', 'rekam_medis'));
    }

    /**
     * Update data profile user
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'nik'     => 'nullable|string|size:16|unique:users,nik,' . Auth::id(),
            'phone'   => 'required|string|max:20|unique:users,phone,' . Auth::id(),
            'email'   => 'required|email|unique:users,email,' . Auth::id(),
            'address' => 'nullable|string|max:255',
        ], [
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
        ]);

        $user = Auth::user();

        $user->update([
            'name'    => $request->name,
            'nik'     => $request->nik,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Lihat detail rekam medis (untuk user)
     */
    public function showMedicalRecord($id)
    {
        $user = Auth::user();
        
        // Pastikan rekam medis milik user yang login
        $record = MedicalRecord::where('patient_id', $user->id)
                    ->with('terapis')
                    ->findOrFail($id);

        return view('profile.medical-record-detail', compact('record'));
    }
}