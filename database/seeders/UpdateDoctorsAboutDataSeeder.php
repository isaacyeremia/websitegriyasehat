<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class UpdateDoctorsAboutDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            1 => [ // Catty Santoso
                'daftar_harga' => [
                    'Pendaftaran Rp50.000',
                    'Akupuntur Biasa (20–30 menit) Rp175.000',
                    'Akupuntur Kilat (5–10 menit) Rp50.000 / keluhan',
                    'Akupuntur Mengeluarkan Darah Rp100.000',
                    'Kop Tinggal Rp50.000',
                    'Kop Kilat Rp50.000',
                    'Kop Jalan Rp100.000',
                    'Kop Mengeluarkan Darah Rp150.000'
                ],
                'urutan' => 1,
                'show_in_about' => true,
            ],
            2 => [ // Retnawati
                'daftar_harga' => [
                    'Akupuntur Rp175.000',
                    'Akupuntur Cepat Rp50.000',
                    'Kop Jalan Rp50.000',
                    'Kop Tinggal Rp50.000',
                    'Kop Lengkap Rp100.000',
                    'Konsultasi + Resep Rp50.000',
                    'Pendaftaran Rp25.000'
                ],
                'urutan' => 2,
                'show_in_about' => true,
            ],
            3 => [ // Alfredo
                'daftar_harga' => [
                    'Pendaftaran Rp50.000',
                    'Akupuntur Biasa Rp175.000',
                    'Akupuntur Kilat Rp50.000 / keluhan',
                    'Akupuntur Mengeluarkan Darah Rp100.000',
                    'Kop Tinggal Rp50.000',
                    'Kop Kilat Rp50.000',
                    'Kop Jalan Rp100.000',
                    'Kop Mengeluarkan Darah Rp150.000'
                ],
                'urutan' => 3,
                'show_in_about' => true,
            ],
            4 => [ // Impian
                'daftar_harga' => [
                    'Totok Wajah (25 menit) Rp75.000',
                    'Pengobatan Tradisional Lengkap Rp150.000',
                    'Kop Rp100.000',
                    'Pendaftaran Rp25.000'
                ],
                'urutan' => 4,
                'show_in_about' => true,
            ],
            5 => [ // Fadilla
                'daftar_harga' => [
                    'Pengobatan Tradisional Rp150.000',
                    'Pengobatan Tradisional Khusus ABK Rp125.000',
                    'Kop Rp50.000 / bagian',
                    'Pendaftaran Rp25.000'
                ],
                'urutan' => 5,
                'show_in_about' => true,
            ],
        ];

        foreach ($data as $id => $values) {
            $doctor = Doctor::find($id);
            if ($doctor) {
                $doctor->update($values);
            }
        }
    }
}