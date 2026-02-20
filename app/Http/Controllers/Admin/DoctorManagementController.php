<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query();

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('schedule', 'like', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        // Filter show in about
        if ($request->show_about === 'yes') {
            $query->where('show_in_about', true);
        } elseif ($request->show_about === 'no') {
            $query->where('show_in_about', false);
        }

        $doctor = $query->orderBy('urutan', 'asc')
                         ->orderBy('name', 'asc')
                         ->paginate(10)
                         ->withQueryString();

        return view('admin.terapis.index', ['terapis' => $doctor]);
    }

    public function create()
    {
        return view('admin.terapis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'schedule'       => 'required|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'daftar_harga'   => 'nullable|array',
            'daftar_harga.*' => 'nullable|string',
            'is_active'      => 'nullable|boolean',
            'show_in_about'  => 'nullable|boolean',
            'urutan'         => 'nullable|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('doctors', 'public');
        }

        // Filter empty harga
        $daftarHarga = $request->daftar_harga 
            ? array_filter($request->daftar_harga, fn($v) => !empty(trim($v)))
            : [];

        Doctor::create([
            'name'           => $request->name,
            'schedule'       => $request->schedule,
            'image'          => $imagePath,
            'daftar_harga'   => $daftarHarga,
            'is_active'      => $request->boolean('is_active', true),
            'show_in_about'  => $request->boolean('show_in_about', true),
            'urutan'         => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.terapis.index')
                         ->with('success', 'Dokter berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.terapis.edit', ['terapis' => $doctor]);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'schedule'       => 'required|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'daftar_harga'   => 'nullable|array',
            'daftar_harga.*' => 'nullable|string',
            'is_active'      => 'nullable|boolean',
            'show_in_about'  => 'nullable|boolean',
            'urutan'         => 'nullable|integer|min:0',
        ]);

        $imagePath = $doctor->image;

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($doctor->image) {
                Storage::disk('public')->delete($doctor->image);
            }
            $imagePath = $request->file('image')->store('doctors', 'public');
        }

        // Filter empty harga
        $daftarHarga = $request->daftar_harga 
            ? array_filter($request->daftar_harga, fn($v) => !empty(trim($v)))
            : [];

        $doctor->update([
            'name'           => $request->name,
            'schedule'       => $request->schedule,
            'image'          => $imagePath,
            'daftar_harga'   => $daftarHarga,
            'is_active'      => $request->boolean('is_active'),
            'show_in_about'  => $request->boolean('show_in_about'),
            'urutan'         => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.terapis.index')
                         ->with('success', 'Dokter berhasil diupdate!');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        // Check if doctor has schedules or patient histories
        if ($doctor->schedules()->count() > 0 || $doctor->patientHistories()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'Dokter tidak bisa dihapus karena masih memiliki jadwal atau riwayat pasien!');
        }

        if ($doctor->image) {
            Storage::disk('public')->delete($doctor->image);
        }

        $doctor->delete();

        return redirect()->route('admin.terapis.index')
                         ->with('success', 'Dokter berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update(['is_active' => !$doctor->is_active]);

        return redirect()->back()
                         ->with('success', 'Status dokter berhasil diubah!');
    }

    public function toggleAbout($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update(['show_in_about' => !$doctor->show_in_about]);

        return redirect()->back()
                         ->with('success', 'Status tampilan About berhasil diubah!');
    }
}