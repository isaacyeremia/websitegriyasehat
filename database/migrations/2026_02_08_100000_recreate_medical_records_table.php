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
        // Drop tabel lama jika ada
        Schema::dropIfExists('medical_records');
        
        // Buat ulang dengan struktur yang benar
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('terapis_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('queue_id')->nullable();
            $table->foreign('queue_id')->references('id')->on('patient_histories')->onDelete('set null');
            
            // Data Keluhan
            $table->text('complaint')->nullable(); // Keluhan
            $table->text('anamnesis')->nullable(); // Anamnesis - NULLABLE
            $table->text('riwayat_penyakit')->nullable(); // Riwayat Penyakit
            
            // Diagnosis
            $table->string('diagnosis')->nullable(); // Diagnosis umum
            $table->string('diagnosis_awal')->nullable(); // Diagnosis Awal
            $table->string('diagnosis_akhir')->nullable(); // Diagnosis Akhir
            
            // Treatment & Pengobatan
            $table->text('treatment')->nullable(); // Treatment/Tindakan
            $table->text('pengobatan')->nullable(); // Pengobatan detail
            
            // Obat
            $table->text('medicine')->nullable(); // Medicine
            $table->text('obat_diberikan')->nullable(); // Obat yang diberikan
            
            // Catatan
            $table->text('doctor_note')->nullable(); // Catatan dokter
            $table->text('catatan_tambahan')->nullable(); // Catatan tambahan
            
            // Tanggal Pemeriksaan
            $table->date('checkup_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};