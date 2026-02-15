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
            // Tambah kolom patient_email dan patient_phone jika belum ada
            if (!Schema::hasColumn('patient_histories', 'patient_email')) {
                $table->string('patient_email', 100)->nullable()->after('patient_nik');
            }
            
            if (!Schema::hasColumn('patient_histories', 'patient_phone')) {
                $table->string('patient_phone', 20)->nullable()->after('patient_email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            if (Schema::hasColumn('patient_histories', 'patient_email')) {
                $table->dropColumn('patient_email');
            }
            
            if (Schema::hasColumn('patient_histories', 'patient_phone')) {
                $table->dropColumn('patient_phone');
            }
        });
    }
};