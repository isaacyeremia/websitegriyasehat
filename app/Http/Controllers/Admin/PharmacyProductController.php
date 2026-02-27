<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PharmacyProduct;
use Illuminate\Http\Request;

class PharmacyProductController extends Controller
{
    // ================================
    // KONFIGURASI GAMBAR
    // ================================
    const IMAGE_MAX_WIDTH  = 800;
    const IMAGE_MAX_HEIGHT = 800;
    const IMAGE_QUALITY    = 85;
    const IMAGE_DIR        = 'images/pharmacy-products';

    // Kategori tersedia (satu sumber kebenaran, dipakai controller & view)
    const CATEGORIES = [
        'minyak'   => 'Minyak Gosok',
        'herbal'   => 'Obat Herbal',
        'suplemen' => 'Suplemen',
        'sirup'    => 'Sirup',
    ];

    // ================================
    // INDEX
    // ================================
    public function index(Request $request)
    {
        $query = PharmacyProduct::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'oldest'     => $query->orderBy('created_at', 'asc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            'name_desc'  => $query->orderBy('name', 'desc'),
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $products   = $query->paginate(15)->withQueryString();
        $categories = self::CATEGORIES;

        return view('admin.pharmacy.index', compact('products', 'categories'));
    }

    // ================================
    // CREATE
    // ================================
    public function create()
    {
        $categories = self::CATEGORIES;
        return view('admin.pharmacy.create', compact('categories'));
    }

    // ================================
    // STORE
    // ================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'image'          => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'category'       => 'required|in:' . implode(',', array_keys(self::CATEGORIES)),
            'description'    => 'nullable|string|max:1000',
            'tokopedia_link' => 'nullable|url',
            'is_active'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Simpan path lengkap ke DB: images/pharmacy-products/filename.jpg
            $validated['image'] = self::IMAGE_DIR . '/' . $this->processAndSaveImage($request->file('image'));
        }

        $validated['is_active'] = $request->has('is_active');

        PharmacyProduct::create($validated);

        return redirect()->route('admin.pharmacy.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    // ================================
    // EDIT
    // ================================
    public function edit($id)
    {
        $product    = PharmacyProduct::findOrFail($id);
        $categories = self::CATEGORIES;
        return view('admin.pharmacy.edit', compact('product', 'categories'));
    }

    // ================================
    // UPDATE
    // ================================
    public function update(Request $request, $id)
    {
        $product = PharmacyProduct::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'category'       => 'required|in:' . implode(',', array_keys(self::CATEGORIES)),
            'description'    => 'nullable|string|max:1000',
            'tokopedia_link' => 'nullable|url',
            'is_active'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Hapus file lama, lalu simpan path lengkap yang baru
            $this->deleteImage($product->image);
            $validated['image'] = self::IMAGE_DIR . '/' . $this->processAndSaveImage($request->file('image'));
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.pharmacy.index')
                         ->with('success', 'Produk berhasil diupdate!');
    }

    // ================================
    // DESTROY
    // ================================
    public function destroy($id)
    {
        $product = PharmacyProduct::findOrFail($id);
        $this->deleteImage($product->image);
        $product->delete();

        return redirect()->route('admin.pharmacy.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

    // ================================
    // TOGGLE STATUS
    // ================================
    public function toggleStatus($id)
    {
        $product            = PharmacyProduct::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.pharmacy.index')
                         ->with('success', "Produk {$product->name} berhasil {$status}!");
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    /**
     * Resolve folder tujuan upload.
     * Dicoba satu per satu hingga ketemu folder yang writable.
     */
    private function getImageDir(): string
    {
        $candidates = [
            base_path('../public_html/' . self::IMAGE_DIR),  // shared hosting Hostinger
            public_path(self::IMAGE_DIR),                     // Laravel standard
            base_path('public/' . self::IMAGE_DIR),           // fallback explicit
        ];

        foreach ($candidates as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            if (is_dir($dir) && is_writable($dir)) {
                return $dir;
            }
        }

        return public_path(self::IMAGE_DIR);
    }

    /**
     * Proses resize gambar dan simpan ke disk.
     * Mengembalikan hanya NAMA FILE (tanpa path), e.g. "prod_abc123.webp"
     * Path lengkap (IMAGE_DIR/filename) disusun di store() dan update().
     */
    private function processAndSaveImage($file): string
    {
        $dir      = $this->getImageDir();
        $mime     = $file->getMimeType();
        $realPath = $file->getRealPath();

        $gdInfo    = function_exists('gd_info') ? gd_info() : [];
        $webpRead  = function_exists('imagecreatefromwebp') && !empty($gdInfo['WebP Support']);
        $webpWrite = function_exists('imagewebp') && !empty($gdInfo['WebP Support']);

        // Load source image sesuai mime
        $src = null;
        if ($mime === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
            $src = imagecreatefromjpeg($realPath);
        } elseif ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
            $src = $this->pngToTruecolor($realPath);
        } elseif ($mime === 'image/gif' && function_exists('imagecreatefromgif')) {
            $src = imagecreatefromgif($realPath);
        } elseif ($mime === 'image/webp' && $webpRead) {
            $src = imagecreatefromwebp($realPath);
        } elseif (function_exists('imagecreatefromstring')) {
            $src = imagecreatefromstring(file_get_contents($realPath));
        }

        // Jika GD tidak bisa baca, simpan file apa adanya
        if (!$src) {
            $ext      = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
            $filename = uniqid('prod_') . '.' . $ext;
            $file->move($dir, $filename);
            return $filename;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);
        [$newW, $newH] = $this->calcResizeDimensions($origW, $origH, self::IMAGE_MAX_WIDTH, self::IMAGE_MAX_HEIGHT);

        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Simpan WebP jika didukung, fallback JPG
        if ($webpWrite) {
            $filename = uniqid('prod_') . '.webp';
            imagewebp($dst, $dir . DIRECTORY_SEPARATOR . $filename, self::IMAGE_QUALITY);
        } else {
            $filename = uniqid('prod_') . '.jpg';
            imagejpeg($dst, $dir . DIRECTORY_SEPARATOR . $filename, self::IMAGE_QUALITY);
        }

        imagedestroy($src);
        imagedestroy($dst);

        return $filename;
    }

    private function calcResizeDimensions(int $origW, int $origH, int $maxW, int $maxH): array
    {
        if ($origW <= $maxW && $origH <= $maxH) {
            return [$origW, $origH];
        }
        $ratio = min($maxW / $origW, $maxH / $origH);
        return [(int) round($origW * $ratio), (int) round($origH * $ratio)];
    }

    private function pngToTruecolor(string $path)
    {
        $img = imagecreatefrompng($path);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        return $img;
    }

    /**
     * Hapus file gambar dari disk.
     * $image = path lengkap dari DB, e.g. "images/pharmacy-products/prod_abc.jpg"
     */
    private function deleteImage(?string $image): void
    {
        if (!$image) return;

        // Ambil hanya nama file dari path DB
        $filename = basename($image);

        $candidates = [
            base_path('../public_html/' . self::IMAGE_DIR . DIRECTORY_SEPARATOR . $filename),
            public_path(self::IMAGE_DIR . DIRECTORY_SEPARATOR . $filename),
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                @unlink($path);
                break;
            }
        }
    }
}