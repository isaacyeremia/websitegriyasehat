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
        Schema::table('patient_histories', function (Blueprint $table) {
            // Ubah kolom lama jadi nullable (opsional, jika mau tetap pakai)
            $table->string('patient_name')->nullable()->change();
            $table->string('service')->nullable()->change();
            $table->date('visit_date')->nullable()->change();
            
            // Tambah kolom baru untuk sistem antrian
            $table->string('kode_antrian')->nullable()->after('id');
            $table->string('poli')->nullable();
            $table->string('dokter')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('keluhan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            $table->dropColumn(['kode_antrian', 'poli', 'dokter', 'tanggal', 'keluhan']);
        });
    }
};