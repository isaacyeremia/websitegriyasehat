<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorManagementController extends Controller
{
    private function imagePath(): string
    {
        return public_path('../images/tenaga-medis');
    }
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

        $terapis = $query->orderBy('urutan', 'asc')
                         ->orderBy('name', 'asc')
                         ->paginate(10)
                         ->withQueryString();

        return view('admin.terapis.index', ['terapis' => $terapis]);
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

        $imageName = null;
    if ($request->hasFile('image')) {
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move($this->imagePath(), $imageName);
    }

        // Filter empty harga
        $daftarHarga = $request->daftar_harga 
            ? array_filter($request->daftar_harga, fn($v) => !empty(trim($v)))
            : [];

            Doctor::create([
            'name'          => $request->name,
            'schedule'      => $request->schedule,
            'image'         => $imageName,  // simpan nama file saja, bukan path
            'daftar_harga'  => $daftarHarga,
            'is_active'     => $request->boolean('is_active', true),
            'show_in_about' => $request->boolean('show_in_about', true),
            'urutan'        => $request->urutan ?? 0,
        ]);
    
        return redirect()->route('admin.terapis.index')
                         ->with('success', 'Dokter berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $terapis = Doctor::findOrFail($id);
        return view('admin.terapis.edit', ['terapis' => $terapis]);
    }

    public function update(Request $request, $id)
    {
        $terapis = Doctor::findOrFail($id);

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

            $imageName = $terapis->image;
    
        if ($request->hasFile('image')) {
        if ($terapis->image) {
            $oldFile = $this->imagePath() . '/' . $terapis->image;
            if (file_exists($oldFile)) unlink($oldFile); // ← fix: fileexists bukan fileexists
        }
        $imageName = time() . '' . $request->file('image')->getClientOriginalName(); // ← fix: '_' bukan ''
        $request->file('image')->move($this->imagePath(), $imageName);
    }

        // Filter empty harga
        $daftarHarga = $request->daftar_harga 
            ? array_filter($request->daftar_harga, fn($v) => !empty(trim($v)))
            : [];

            $terapis->update([
            'name'          => $request->name,
            'schedule'      => $request->schedule,
            'image'         => $imageName,
            'daftar_harga'  => $daftarHarga,
            'is_active'     => $request->boolean('is_active'),
            'show_in_about' => $request->boolean('show_in_about'),
            'urutan'        => $request->urutan ?? 0,
        ]);
    
        return redirect()->route('admin.terapis.index')
                         ->with('success', 'Dokter berhasil diupdate!');
    }

    public function destroy($id)
    {
        $terapis = Doctor::findOrFail($id);

        // Check if doctor has schedules or patient histories
        if ($terapis->schedules()->count() > 0 || $terapis->patientHistories()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'Dokter tidak bisa dihapus karena masih memiliki jadwal atau riwayat pasien!');
        }

        if ($terapis->image) {
        $file = $this->imagePath() . '/' . $terapis->image;
        if (file_exists($file)) unlink($file);
    }

        $terapis->delete();

        return redirect()->route('admin.terapis.index')
                         ->with('success', 'Dokter berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $terapis = Doctor::findOrFail($id);
        $terapis->update(['is_active' => !$terapis->is_active]);

        return redirect()->back()
                         ->with('success', 'Status dokter berhasil diubah!');
    }

    public function toggleAbout($id)
    {
        $terapis = Doctor::findOrFail($id);
        $terapis->update(['show_in_about' => !$terapis->show_in_about]);

        return redirect()->back()
                         ->with('success', 'Status tampilan About berhasil diubah!');
    }
}