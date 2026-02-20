<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('doctors', function (Blueprint $table) {
        $table->renameColumn('specialization', 'kondisi_minat_klinis');
    });
}

public function down(): void
{
    Schema::table('doctors', function (Blueprint $table) {
        $table->renameColumn('kondisi_minat_klinis', 'specialization');
    });
}
};
