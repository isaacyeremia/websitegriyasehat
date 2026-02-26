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

    public function create()
    {
        $categories = self::CATEGORIES;
        return view('admin.pharmacy.create', compact('categories'));
    }

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
            $validated['image'] = $this->processAndSaveImage($request->file('image'));
        }

        $validated['is_active'] = $request->has('is_active');

        PharmacyProduct::create($validated);

        return redirect()->route('admin.pharmacy.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product    = PharmacyProduct::findOrFail($id);
        $categories = self::CATEGORIES;
        return view('admin.pharmacy.edit', compact('product', 'categories'));
    }

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
            $this->deleteImage($product->image);
            $validated['image'] = $this->processAndSaveImage($request->file('image'));
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.pharmacy.index')
                         ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = PharmacyProduct::findOrFail($id);
        $this->deleteImage($product->image);
        $product->delete();

        return redirect()->route('admin.pharmacy.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

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
            base_path('../public_html/' . self::IMAGE_DIR),  // shared hosting: /home/user/public_html/...
            public_path(self::IMAGE_DIR),                     // Laravel standard: /var/www/html/public/...
            base_path('public/' . self::IMAGE_DIR),           // fallback explicit
        ];

        foreach ($candidates as $dir) {
            // Coba buat folder jika belum ada
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            // Pakai folder ini jika berhasil dibuat / sudah ada dan writable
            if (is_dir($dir) && is_writable($dir)) {
                return $dir;
            }
        }

        // Last resort: public_path (biarkan error alami jika tidak bisa tulis)
        return public_path(self::IMAGE_DIR);
    }

    private function processAndSaveImage($file): string
    {
        $dir = $this->getImageDir();

        $mime     = $file->getMimeType();
        $realPath = $file->getRealPath();

        // Cek fitur GD yang tersedia di instalasi PHP ini
        $gdInfo    = function_exists('gd_info') ? gd_info() : [];
        $webpRead  = function_exists('imagecreatefromwebp') && !empty($gdInfo['WebP Support']);
        $webpWrite = function_exists('imagewebp') && !empty($gdInfo['WebP Support']);

        // Load source image sesuai mime type
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

        // Simpan: WebP jika didukung, JPG sebagai fallback
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

    private function deleteImage(?string $image): void
    {
        if (!$image) return;
        if (!str_contains($image, '/')) {
            // Coba hapus dari semua kemungkinan lokasi
            $candidates = [
                base_path('../public_html/' . self::IMAGE_DIR . DIRECTORY_SEPARATOR . basename($image)),
                public_path(self::IMAGE_DIR . DIRECTORY_SEPARATOR . basename($image)),
            ];
            foreach ($candidates as $path) {
                if (file_exists($path)) {
                    @unlink($path);
                    break;
                }
            }
        }
    }
}