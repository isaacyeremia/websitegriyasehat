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

            // relasi ke user (akun login)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // data riwayat pasien
            $table->string('patient_name');
            $table->date('visit_date');
            $table->string('service'); // Akupuntur, Herbal, dll
            $table->string('status');  // Selesai, Proses, Batal

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
