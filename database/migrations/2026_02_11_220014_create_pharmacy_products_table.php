<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama produk
            $table->string('image')->nullable(); // Path gambar produk
            $table->decimal('price', 12, 2); // Harga
            $table->string('tokopedia_link')->nullable(); // Link Tokopedia
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_products');
    }
};