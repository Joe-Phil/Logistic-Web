<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// =====================
// CRUD PRODUK
// =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');

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
});

require __DIR__.'/settings.php';
