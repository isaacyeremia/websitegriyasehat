<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PharmacyProductSeeder extends Seeder
{
    /**
     * Jalankan dengan: php artisan db:seed --class=PharmacyProductSeeder
     *
     * CATATAN: Gambar hardcoded yang lama ada di public/tokopedia/*.jpg
     * Seeder ini menyimpan path tersebut di kolom image apa adanya.
     * Kalau mau, upload ulang gambarnya via halaman admin agar ter-convert ke WebP.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $products = [
            // ================= MINYAK GOSOK =================
            [
                'name'           => 'Minyak Gosok Pijat Cap Tangga',
                'price'          => 150000,
                'image'          => 'tokopedia/minyak-cap-tangga.jpg',
                'category'       => 'minyak',
                'description'    => 'Minyak gosok pijat Cap Tangga untuk meredakan pegal dan nyeri otot.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUHyGYT/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Minyak Gosok Cap Tiga Daun',
                'price'          => 50000,
                'image'          => 'tokopedia/minyak-cap-tiga-daun.jpg',
                'category'       => 'minyak',
                'description'    => 'Minyak gosok Cap Tiga Daun dengan formula herbal alami.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUH5yjd/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Minyak Gosok Cap Tunggal 100ml',
                'price'          => 143000,
                'image'          => 'tokopedia/minyak-cap-tunggal-100.jpg',
                'category'       => 'minyak',
                'description'    => 'Minyak gosok Cap Tunggal kemasan 100ml.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUH7esR/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Minyak Gosok Cap Tunggal 50ml',
                'price'          => 85000,
                'image'          => 'tokopedia/minyak-cap-tunggal-50.jpg',
                'category'       => 'minyak',
                'description'    => 'Minyak gosok Cap Tunggal kemasan 50ml.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUHo4HP/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Minyak Gosok Cap Tunggal 30ml',
                'price'          => 55000,
                'image'          => 'tokopedia/minyak-cap-tunggal-30.jpg',
                'category'       => 'minyak',
                'description'    => 'Minyak gosok Cap Tunggal kemasan 30ml.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUHEf1Q/',
                'is_active'      => true,
            ],

            // ================= OBAT HERBAL =================
            [
                'name'           => 'Haiping Serbuk Obat Herbal Pelangsing',
                'price'          => 4100,
                'image'          => 'tokopedia/haiping-serbuk.jpg',
                'category'       => 'herbal',
                'description'    => 'Haiping serbuk herbal untuk membantu program pelangsing tubuh.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9kXcL/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Haiping Kapsul Herbal Pelangsing',
                'price'          => 17800,
                'image'          => 'tokopedia/haiping-kapsul.jpg',
                'category'       => 'herbal',
                'description'    => 'Haiping kapsul herbal untuk membantu program pelangsing tubuh.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9RMGJ/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Topicin Kapsul Pelancar Haid',
                'price'          => 25000,
                'image'          => 'tokopedia/topicin-kapsul.jpg',
                'category'       => 'herbal',
                'description'    => 'Topicin kapsul herbal untuk memperlancar siklus haid.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUHE4WD/',
                'is_active'      => true,
            ],
            [
                'name'           => 'HERBALEGA Obat Herbal Masuk Angin',
                'price'          => 200000,
                'image'          => 'tokopedia/herbalega.jpg',
                'category'       => 'herbal',
                'description'    => 'Herbalega obat herbal untuk mengatasi masuk angin dan kembung.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU97Tx4/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Herba Adem Obat Herbal Anti Infeksi',
                'price'          => 200000,
                'image'          => 'tokopedia/herba-adem.jpg',
                'category'       => 'herbal',
                'description'    => 'Herba Adem formulasi herbal anti infeksi untuk daya tahan tubuh.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUxduMg/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Teen Sadha Obat Herbal Kesehatan',
                'price'          => 200000,
                'image'          => 'tokopedia/teen-sadha.jpg',
                'category'       => 'herbal',
                'description'    => 'Teen Sadha obat herbal untuk menjaga kesehatan remaja.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9TRjf/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Arana Awan Obat Herbal Pereda Pegal',
                'price'          => 170000,
                'image'          => 'tokopedia/arana-awan.jpg',
                'category'       => 'herbal',
                'description'    => 'Arana Awan herbal untuk meredakan pegal linu dan nyeri sendi.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUxueWM/',
                'is_active'      => true,
            ],

            // ================= SIRUP =================
            [
                'name'           => 'Topicin Sirup Herbal 60ml',
                'price'          => 31000,
                'image'          => 'tokopedia/topicin-sirup-60.jpg',
                'category'       => 'sirup',
                'description'    => 'Topicin sirup herbal kemasan 60ml untuk kesehatan.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9DkRa/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Renatin Syrup 300ml Peluruh Batu Ginjal',
                'price'          => 40000,
                'image'          => 'tokopedia/renatin-syrup.jpg',
                'category'       => 'sirup',
                'description'    => 'Renatin Syrup 300ml untuk membantu meluruhkan batu ginjal.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9hrwT/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Izzifit Syrup Penambah Nafsu Makan 60ml',
                'price'          => 40000,
                'image'          => 'tokopedia/izzifit-syrup.jpg',
                'category'       => 'sirup',
                'description'    => 'Izzifit Syrup 60ml untuk meningkatkan nafsu makan.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9PpmG/',
                'is_active'      => true,
            ],

            // ================= SUPLEMEN =================
            [
                'name'           => 'Naturertown Golden Cordyceps Capsule',
                'price'          => 288000,
                'image'          => 'tokopedia/cordyceps.jpg',
                'category'       => 'suplemen',
                'description'    => 'Suplemen kapsul Cordyceps untuk stamina dan imunitas tubuh.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9ajno/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Typhonium Plus Kapsul',
                'price'          => 207000,
                'image'          => 'tokopedia/typhonium-plus.jpg',
                'category'       => 'suplemen',
                'description'    => 'Typhonium Plus kapsul herbal untuk kesehatan dan daya tahan tubuh.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9GgSo/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Sun Kidney Capsule',
                'price'          => 338000,
                'image'          => 'tokopedia/sun-kidney.jpg',
                'category'       => 'suplemen',
                'description'    => 'Sun Kidney kapsul suplemen untuk kesehatan ginjal.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUxR7rS/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Livergyn Ekstrak Temulawak',
                'price'          => 82000,
                'image'          => 'tokopedia/livergyn.jpg',
                'category'       => 'suplemen',
                'description'    => 'Livergyn ekstrak temulawak untuk menjaga kesehatan hati dan liver.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9wDN5/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Noni Herbal Capsule',
                'price'          => 88000,
                'image'          => 'tokopedia/noni.jpg',
                'category'       => 'suplemen',
                'description'    => 'Noni Herbal kapsul dari ekstrak mengkudu untuk kesehatan optimal.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUx8tke/',
                'is_active'      => true,
            ],
            [
                'name'           => 'ICP Herbal Jantung',
                'price'          => 548000,
                'image'          => 'tokopedia/icp.jpg',
                'category'       => 'suplemen',
                'description'    => 'ICP Herbal suplemen untuk mendukung kesehatan jantung.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUx8CaT/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Biodarium Herbal',
                'price'          => 135000,
                'image'          => 'tokopedia/biodarium.jpg',
                'category'       => 'suplemen',
                'description'    => 'Biodarium herbal suplemen untuk keseimbangan kesehatan tubuh.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPU9C2MG/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Royal Sadha Penguat Ginjal dan Hati',
                'price'          => 200000,
                'image'          => 'tokopedia/royal-sadha.jpg',
                'category'       => 'suplemen',
                'description'    => 'Royal Sadha herbal untuk memperkuat fungsi ginjal dan hati.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUxJbpp/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Uriflush Kapsul Peluruh Batu Ginjal',
                'price'          => 82000,
                'image'          => 'tokopedia/uriflush.jpg',
                'category'       => 'suplemen',
                'description'    => 'Uriflush kapsul herbal untuk membantu meluruhkan batu ginjal.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUxDh31/',
                'is_active'      => true,
            ],
            [
                'name'           => 'Javakolesto Pengurang Lemak Darah',
                'price'          => 82000,
                'image'          => 'tokopedia/javakolesto.jpg',
                'category'       => 'suplemen',
                'description'    => 'Javakolesto herbal untuk membantu menurunkan kadar lemak darah.',
                'tokopedia_link' => 'https://tk.tokopedia.com/ZSPUxXwqu/',
                'is_active'      => true,
            ],
        ];

        foreach ($products as $product) {
            DB::table('pharmacy_products')->insertOrIgnore([
                ...$product,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✅ ' . count($products) . ' produk apotek berhasil di-seed!');
    }
}
