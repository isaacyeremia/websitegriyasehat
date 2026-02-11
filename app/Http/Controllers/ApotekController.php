<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PharmacyProduct;

class ApotekController extends Controller
{
    public function index()
    {
        // Produk dari database
        $dbProducts = PharmacyProduct::where('is_active', true)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function($product) {
                            return [
                                'name' => $product->name,
                                'price' => $product->price,
                                'image' => $product->image, // PENTING: TANPA prefix 'storage/'
                                'link' => $product->tokopedia_link,
                                'category' => 'database', // Penanda produk dari database
                            ];
                        })
                        ->toArray();

        // Produk hardcoded (yang sudah ada)
        $hardcodedProducts = [
            // ================= MINYAK GOSOK =================
            [
                'name' => 'Minyak Gosok Pijat Cap Tangga',
                'price' => 150000,
                'image' => 'tokopedia/minyak-cap-tangga.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUHyGYT/',
                'category' => 'minyak'
            ],
            [
                'name' => 'Minyak Gosok Cap Tiga Daun',
                'price' => 50000,
                'image' => 'tokopedia/minyak-cap-tiga-daun.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUH5yjd/',
                'category' => 'minyak'
            ],
            [
                'name' => 'Minyak Gosok Cap Tunggal 100ml',
                'price' => 143000,
                'image' => 'tokopedia/minyak-cap-tunggal-100.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUH7esR/',
                'category' => 'minyak'
            ],
            [
                'name' => 'Minyak Gosok Cap Tunggal 50ml',
                'price' => 85000,
                'image' => 'tokopedia/minyak-cap-tunggal-50.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUHo4HP/',
                'category' => 'minyak'
            ],
            [
                'name' => 'Minyak Gosok Cap Tunggal 30ml',
                'price' => 55000,
                'image' => 'tokopedia/minyak-cap-tunggal-30.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUHEf1Q/',
                'category' => 'minyak'
            ],

            // ================= OBAT HERBAL =================
            [
                'name' => 'Haiping Serbuk Obat Herbal Pelangsing',
                'price' => 4100,
                'image' => 'tokopedia/haiping-serbuk.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9kXcL/',
                'category' => 'herbal'
            ],
            [
                'name' => 'Haiping Kapsul Herbal Pelangsing',
                'price' => 17800,
                'image' => 'tokopedia/haiping-kapsul.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9RMGJ/',
                'category' => 'herbal'
            ],
            [
                'name' => 'Topicin Kapsul Pelancar Haid',
                'price' => 25000,
                'image' => 'tokopedia/topicin-kapsul.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUHE4WD/',
                'category' => 'herbal'
            ],

            // ================= SIRUP =================
            [
                'name' => 'Topicin Sirup Herbal 60ml',
                'price' => 31000,
                'image' => 'tokopedia/topicin-sirup-60.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9DkRa/',
                'category' => 'sirup'
            ],
            [
                'name' => 'Renatin Syrup 300ml Peluruh Batu Ginjal',
                'price' => 40000,
                'image' => 'tokopedia/renatin-syrup.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9hrwT/',
                'category' => 'sirup'
            ],
            [
                'name' => 'Izzifit Syrup Penambah Nafsu Makan 60ml',
                'price' => 40000,
                'image' => 'tokopedia/izzifit-syrup.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9PpmG/',
                'category' => 'sirup'
            ],

            // ================= SUPLEMEN =================
            [
                'name' => 'Naturertown Golden Cordyceps Capsule',
                'price' => 288000,
                'image' => 'tokopedia/cordyceps.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9ajno/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'Typhonium Plus Kapsul',
                'price' => 207000,
                'image' => 'tokopedia/typhonium-plus.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9GgSo/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'Sun Kidney Capsule',
                'price' => 338000,
                'image' => 'tokopedia/sun-kidney.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUxR7rS/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'Livergyn Ekstrak Temulawak',
                'price' => 82000,
                'image' => 'tokopedia/livergyn.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9wDN5/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'Noni Herbal Capsule',
                'price' => 88000,
                'image' => 'tokopedia/noni.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUx8tke/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'ICP Herbal Jantung',
                'price' => 548000,
                'image' => 'tokopedia/icp.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUx8CaT/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'Biodarium Herbal',
                'price' => 135000,
                'image' => 'tokopedia/biodarium.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9C2MG/',
                'category' => 'suplemen'
            ],

            // ================= PRODUK TAMBAHAN =================
            [
                'name' => 'HERBALEGA Obat Herbal Masuk Angin',
                'price' => 200000,
                'image' => 'tokopedia/herbalega.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU97Tx4/',
                'category' => 'herbal'
            ],
            [
                'name' => 'Herba Adem Obat Herbal Anti Infeksi',
                'price' => 200000,
                'image' => 'tokopedia/herba-adem.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUxduMg/',
                'category' => 'herbal'
            ],
            [
                'name' => 'Royal Sadha Penguat Ginjal dan Hati',
                'price' => 200000,
                'image' => 'tokopedia/royal-sadha.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUxJbpp/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'Teen Sadha Obat Herbal Kesehatan',
                'price' => 200000,
                'image' => 'tokopedia/teen-sadha.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPU9TRjf/',
                'category' => 'herbal'
            ],
            [
                'name' => 'Arana Awan Obat Herbal Pereda Pegal',
                'price' => 170000,
                'image' => 'tokopedia/arana-awan.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUxueWM/',
                'category' => 'herbal'
            ],
            [
                'name' => 'Uriflush Kapsul Peluruh Batu Ginjal',
                'price' => 82000,
                'image' => 'tokopedia/uriflush.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUxDh31/',
                'category' => 'suplemen'
            ],
            [
                'name' => 'Javakolesto Pengurang Lemak Darah',
                'price' => 82000,
                'image' => 'tokopedia/javakolesto.jpg',
                'link'  => 'https://tk.tokopedia.com/ZSPUxXwqu/',
                'category' => 'suplemen'
            ],
        ];

        // Gabungkan produk database di awal, lalu produk hardcoded
        $products = array_merge($dbProducts, $hardcodedProducts);

        return view('apotek.index', compact('products'));
    }
}