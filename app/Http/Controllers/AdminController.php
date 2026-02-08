<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientHistory;

class AdminController extends Controller
{
    // Tampilkan dashboard admin dengan semua antrian
    public function dashboard()
    {
        $antrians = PatientHistory::with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        $statistik = [
            'total' => PatientHistory::count(),
            'menunggu' => PatientHistory::where('status', 'Menunggu')->count(),
            'selesai' => PatientHistory::where('status', 'Selesai')->count(),
        ];

        return view('admin.dashboard', compact('antrians', 'statistik'));
    }

    // Update status antrian
    public function updateStatus(Request $request, $id)
    {
        $antrian = PatientHistory::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:Menunggu,Dipanggil,Selesai,Dibatalkan'
        ]);

        $antrian->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status antrian berhasil diupdate');
    }

    // Hapus antrian
    public function destroy($id)
    {
        $antrian = PatientHistory::findOrFail($id);
        $antrian->delete();

        return redirect()->back()->with('success', 'Antrian berhasil dihapus');
    }
}