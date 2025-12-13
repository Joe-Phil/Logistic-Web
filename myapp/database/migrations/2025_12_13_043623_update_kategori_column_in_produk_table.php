<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Mengubah kolom kategori agar punya default value 'Umum'
            $table->string('kategori')->default('Umum')->change();
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Kembalikan menjadi NOT NULL tanpa default jika rollback
            $table->string('kategori')->nullable(false)->default(null)->change();
        });
    }
};
