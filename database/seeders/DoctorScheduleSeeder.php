<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Contoh: Setiap dokter praktek Senin-Jumat
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            
            foreach ($days as $day) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00',
                    'end_time' => '17:00',
                    'quota' => 20,
                    'is_active' => true,
                ]);
            }
        }
    }
}