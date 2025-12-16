<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // 1. Change column type to match produk.id
    Schema::table('produk_stock_logs', function (Blueprint $table) {
        $table->integer('produk_id')->nullable()->change();
    });

    // 2. Add foreign key (now valid)
    Schema::table('produk_stock_logs', function (Blueprint $table) {
        $table->foreign('produk_id')
              ->references('id')
              ->on('produk')
              ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('produk_stock_logs', function (Blueprint $table) {
        $table->dropForeign(['produk_id']);
        $table->unsignedInteger('produk_id')->nullable()->change();
    });
}

};
