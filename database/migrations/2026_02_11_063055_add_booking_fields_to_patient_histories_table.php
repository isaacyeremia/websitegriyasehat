<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            // Tambah field untuk jam praktek
            $table->time('appointment_time')->nullable()->after('tanggal');
            
            // Tambah field untuk konfirmasi kedatangan
            $table->enum('arrival_status', ['Belum Hadir', 'Sudah Hadir', 'Tidak Hadir'])
                  ->default('Belum Hadir')
                  ->after('status');
            
            // Tambah field untuk waktu konfirmasi
            $table->timestamp('confirmed_at')->nullable()->after('arrival_status');
        });
    }

    public function down(): void
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            $table->dropColumn(['appointment_time', 'arrival_status', 'confirmed_at']);
        });
    }
};