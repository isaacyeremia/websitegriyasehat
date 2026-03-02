<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promosi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromosiController extends Controller
{
    private function imageDir(): string
    {
        return base_path('images/promosi');
    }

    /* ── INDEX ── */
    public function index()
    {
        $promosis = Promosi::orderBy('urutan')->orderBy('created_at', 'desc')->get();
        return view('admin.promosi.index', compact('promosis'));
    }

    /* ── CREATE ── */
    public function create()
    {
        return view('admin.promosi.form', ['promosi' => null]);
    }

    /* ── STORE ── */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['manfaat']   = $this->parseManfaat($request->manfaat_raw);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['gambar']    = $this->handleUpload($request, null);

        Promosi::create($data);

        return redirect()->route('admin.promosi.index')
                         ->with('success', 'Promosi berhasil ditambahkan.');
    }

    /* ── EDIT ── */
    public function edit(Promosi $promosi)
    {
        return view('admin.promosi.form', compact('promosi'));
    }

    /* ── UPDATE ── */
    public function update(Request $request, Promosi $promosi)
    {
        $data = $this->validated($request);
        $data['manfaat']   = $this->parseManfaat($request->manfaat_raw);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('gambar')) {
            $this->deleteOldImage($promosi->getOriginal('gambar'));
            $data['gambar'] = $this->handleUpload($request, null);
        } elseif ($request->input('hapus_gambar') == '1') {
            $this->deleteOldImage($promosi->getOriginal('gambar'));
            $data['gambar'] = null;
        } else {
            $data['gambar'] = $promosi->getOriginal('gambar');
        }

        $promosi->update($data);

        return redirect()->route('admin.promosi.index')
                         ->with('success', 'Promosi berhasil diperbarui.');
    }

    /* ── DESTROY ── */
    public function destroy(Promosi $promosi)
    {
        $this->deleteOldImage($promosi->getOriginal('gambar'));
        $promosi->delete();

        return redirect()->route('admin.promosi.index')
                         ->with('success', 'Promosi berhasil dihapus.');
    }

    /* ── TOGGLE AKTIF ── */
    public function toggleActive(Promosi $promosi)
    {
        $promosi->update(['is_active' => !$promosi->is_active]);
        $status = $promosi->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Promosi berhasil {$status}.");
    }

    /* ══════════════════════════════════════════
       PRIVATE HELPERS
    ══════════════════════════════════════════ */

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul'          => 'required|string|max:255',
            'subjudul'       => 'nullable|string|max:255',
            'deskripsi'      => 'nullable|string',
            'definisi'       => 'nullable|string',
            'bonus'          => 'nullable|string|max:255',
            'gambar'         => 'nullable|file|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'harga_asli'     => 'nullable|integer|min:0',
            'harga_promo'    => 'required|integer|min:0',
            'cta_label'      => 'required|string|max:100',
            'cta_url'        => 'nullable|string|max:255',
            'berlaku_hingga' => 'nullable|date',
            'urutan'         => 'nullable|integer|min:0',
        ]);
    }

    private function handleUpload(Request $request, ?string $existingFile): ?string
    {
        if (!$request->hasFile('gambar')) {
            return $existingFile;
        }

        $dir = $this->imageDir();
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file     = $request->file('gambar');
        $ext      = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(8) . '.' . $ext;
        $file->move($dir, $filename);

        return $filename;
    }

    private function deleteOldImage(?string $filename): void
    {
        if (!$filename) return;
        $path = $this->imageDir() . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Ubah textarea jadi JSON array — SATU level encode saja.
     * Hasil: ["item1","item2"] — bukan "\"[\\\"item1\\\"]\"" 
     */
    private function parseManfaat(?string $raw): ?string
    {
        if (!$raw) return null;
        $items = array_values(array_filter(
            array_map('trim', explode("\n", str_replace("\r", '', $raw)))
        ));
        return empty($items) ? null : json_encode($items, JSON_UNESCAPED_UNICODE);
    }
}