<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PharmacyProduct;
use Carbon\Carbon;

class ApotekController extends Controller
{
    public function index()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $products = PharmacyProduct::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($product) use ($thirtyDaysAgo) {
                $isNewUpload = !str_contains($product->image, '/');
                $imagePath   = $isNewUpload
                    ? 'images/pharmacy-products/' . $product->image
                    : $product->image;

                return [
                    'name'        => $product->name,
                    'price'       => $product->price,
                    'image'       => $imagePath,
                    'link'        => $product->tokopedia_link,
                    'category'    => $product->category,
                    'description' => $product->description,
                    'is_new'      => $product->created_at->gte($thirtyDaysAgo),
                ];
            })
            ->toArray();

        return view('apotek.index', compact('products'));
    }
}

