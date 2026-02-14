<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        try {
            $schedules = DoctorSchedule::with('doctor')
                            ->orderBy('day_of_week')
                            ->orderBy('start_time')
                            ->paginate(20);

            return view('admin.schedules.index', compact('schedules'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $doctors = Doctor::where('is_active', true)->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.schedules.create', compact('doctors', 'days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1|max:100', // PERBAIKAN: Tambahkan validasi quota
            'is_active' => 'boolean',
        ]);

        // Cek apakah sudah ada jadwal untuk dokter di hari yang sama
        $exists = DoctorSchedule::where('doctor_id', $validated['doctor_id'])
                                ->where('day_of_week', $validated['day_of_week'])
                                ->exists();

        if ($exists) {
            return back()->withErrors(['day_of_week' => 'Jadwal untuk dokter ini di hari ' . $validated['day_of_week'] . ' sudah ada.'])->withInput();
        }

        // Set is_active
        $validated['is_active'] = $request->has('is_active') ? true : false;

        DoctorSchedule::create($validated);

        return redirect()->route('admin.schedules.index')
                        ->with('success', 'Jadwal praktek berhasil ditambahkan');
    }

    public function edit($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $doctors = Doctor::where('is_active', true)->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.schedules.edit', compact('schedule', 'doctors', 'days'));
    }

    public function update(Request $request, $id)
    {
        $schedule = DoctorSchedule::findOrFail($id);

        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1|max:100', // PERBAIKAN: Tambahkan validasi quota
            'is_active' => 'boolean',
        ]);

        // Cek duplikasi kecuali untuk record ini sendiri
        $exists = DoctorSchedule::where('doctor_id', $validated['doctor_id'])
                                ->where('day_of_week', $validated['day_of_week'])
                                ->where('id', '!=', $id)
                                ->exists();

        if ($exists) {
            return back()->withErrors(['day_of_week' => 'Jadwal untuk dokter ini di hari ' . $validated['day_of_week'] . ' sudah ada.'])->withInput();
        }

        // Set is_active
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // PERBAIKAN: HAPUS BARIS YANG MENIMPA QUOTA
        // Baris ini yang menyebabkan quota tidak terupdate:
        // $validated['quota'] = $schedule->quota ?? 999; ← DIHAPUS!

        // Update langsung dengan semua field dari form (termasuk quota)
        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
                        ->with('success', 'Jadwal praktek berhasil diupdate');
    }

    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
                        ->with('success', 'Jadwal praktek berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->is_active = !$schedule->is_active;
        $schedule->save();

        $status = $schedule->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.schedules.index')
                        ->with('success', 'Jadwal praktek berhasil ' . $status);
    }
}