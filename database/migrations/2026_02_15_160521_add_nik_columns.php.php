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
        // Tambah kolom NIK ke table users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 20)->nullable()->after('phone');
                $table->index('nik'); // Index untuk pencarian cepat
            }
        });

        // Tambah kolom patient_nik ke table patient_histories
        Schema::table('patient_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('patient_histories', 'patient_nik')) {
                $table->string('patient_nik', 20)->nullable()->after('patient_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nik')) {
                $table->dropIndex(['nik']);
                $table->dropColumn('nik');
            }
        });

        Schema::table('patient_histories', function (Blueprint $table) {
            if (Schema::hasColumn('patient_histories', 'patient_nik')) {
                $table->dropColumn('patient_nik');
            }
        });
    }
};