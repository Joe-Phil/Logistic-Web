<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_stock_logs', function (Blueprint $table) {
            $table->id();

            // Match produk.id (INT)
            $table->unsignedInteger('produk_id');

            $table->integer('perubahan_stok');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');

            $table->string('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('produk_id')
                  ->references('id')
                  ->on('produk')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_stock_logs');
    }
};
