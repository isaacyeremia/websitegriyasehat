<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_histories', function (Blueprint $table) {
            $table->id();

            // Relasi ke user (akun login)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Data antrian pasien
            $table->string('patient_name');
            $table->string('kode_antrian')->unique();
            $table->string('poli');
            $table->string('dokter');
            $table->date('tanggal');
            $table->time('appointment_time')->nullable();
            $table->text('keluhan')->nullable();
            
            // Status
            $table->enum('status', ['Menunggu', 'Dipanggil', 'Selesai', 'Dibatalkan'])
                  ->default('Menunggu');
            $table->enum('arrival_status', ['Belum Hadir', 'Sudah Hadir', 'Tidak Hadir'])
                  ->default('Belum Hadir');
            
            // Timestamp konfirmasi kedatangan
            $table->timestamp('confirmed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_histories');
    }
};