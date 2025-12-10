<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Tambahan kuis web
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';


// Tambahan kuis web
Route::get('/api/produk/list', function () {
    $keyword = request('keyword', '');
    $sortBy  = request('sort_by', 'nama_produk');
    $sortDir = request('sort_dir', 'asc');
    $start   = (int) request('start', 0);
    $limit   = (int) request('limit', 10);

    $query = DB::table('produk');

    if ($keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('nama_produk', 'like', "%$keyword%")
              ->orWhere('kategori', 'like', "%$keyword%");
        });
    }

    $count = $query->count();

    if (in_array($sortBy, ['nama_produk', 'harga', 'stok'])) {
        $query->orderBy($sortBy, $sortDir);
    }

    $produk = $query->offset($start)->limit($limit)->get();

    return response()->json([
        'count' => $count,
        'produk' => $produk
    ]);
});

require __DIR__.'/settings.php';