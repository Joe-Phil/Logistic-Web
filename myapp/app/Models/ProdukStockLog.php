<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukStockLog extends Model
{
    protected $table = 'produk_stock_logs';

    protected $fillable = [
        'produk_id',
        'change',
        'type',
        'description',
    ];
}

