<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctor_schedules', function (Blueprint $table) {
            // Tambah kolom quota jika belum ada
            if (!Schema::hasColumn('doctor_schedules', 'quota')) {
                $table->integer('quota')->default(10)->after('end_time');
            }
            
            // Tambah kolom booked_count untuk tracking
            if (!Schema::hasColumn('doctor_schedules', 'booked_count')) {
                $table->integer('booked_count')->default(0)->after('quota');
            }
        });
    }

    public function down()
    {
        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->dropColumn(['quota', 'booked_count']);
        });
    }
};