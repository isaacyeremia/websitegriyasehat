<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Tambah kolom baru
            $table->json('daftar_harga')->nullable()->after('schedule');
            $table->integer('urutan')->default(0)->after('is_active');
            $table->boolean('show_in_about')->default(true)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['daftar_harga', 'urutan', 'show_in_about']);
        });
    }
};