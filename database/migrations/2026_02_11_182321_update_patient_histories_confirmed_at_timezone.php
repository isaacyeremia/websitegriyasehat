<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            // Ubah tipe kolom confirmed_at ke timestampTz (timezone aware)
            $table->timestampTz('confirmed_at')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->change();
        });
    }
};