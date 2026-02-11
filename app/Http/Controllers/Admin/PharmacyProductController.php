<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PharmacyProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PharmacyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PharmacyProduct::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(15)->withQueryString();

        return view('admin.pharmacy.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pharmacy.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tokopedia_link' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        // Upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pharmacy-products', 'public');
            $validated['image'] = $imagePath;
        }

        // Set is_active
        $validated['is_active'] = $request->has('is_active') ? true : false;

        PharmacyProduct::create($validated);

        return redirect()->route('admin.pharmacy.index')
                        ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = PharmacyProduct::findOrFail($id);
        return view('admin.pharmacy.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = PharmacyProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tokopedia_link' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('pharmacy-products', 'public');
            $validated['image'] = $imagePath;
        }

        // Set is_active
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $product->update($validated);

        return redirect()->route('admin.pharmacy.index')
                        ->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = PharmacyProduct::findOrFail($id);

        // Hapus gambar
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.pharmacy.index')
                        ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Toggle product status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $product = PharmacyProduct::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.pharmacy.index')
                        ->with('success', "Produk {$product->name} berhasil {$status}!");
    }
}