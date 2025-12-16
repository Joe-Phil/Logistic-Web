<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukStockLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->input('search', ''); // ambil input search
    $sortBy = $request->input('sort_by', 'id'); // default sort by id
    $sortDir = $request->input('sort_dir', 'asc'); // default asc

    $produk = Produk::query()
        ->when($search, function($q) use ($search) {
            $q->where('nama_produk', 'like', "%{$search}%"); // search by nama_produk
        })
        ->orderBy($sortBy, $sortDir)
        ->get();

    return Inertia::render('Produk/Index', [
        'produk' => $produk,
        'search' => $search,
        'sort_by' => $sortBy,
        'sort_dir' => $sortDir,
    ]);
    }


    public function create()
    {
        return Inertia::render('Produk/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'kategori' => 'required|string',
        ]);

        $produk = Produk::create($request->all());

        if ($produk->stok) {
            ProdukStockLog::create([
                'produk_id' => $produk->id,
                'change' => $produk->stok,
                'type' => 'in',
                'description' => 'Stok awal produk baru',
            ]);
        }
        return redirect()->route('produk.index');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return Inertia::render('Produk/Edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $stokSebelum = $produk->stok;
        $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'kategori' => 'required|string',
        ]);

        $produk->update($request->all());

        $delta = $produk->stok - $stokSebelum;
        if ($delta !== 0) {
            ProdukStockLog::create([
                'produk_id' => $produk->id,
                'change' => $delta,
                'type' => $delta > 0 ? 'in' : 'out',
                'description' => $delta > 0 ? 'Penambahan stok' : 'Pengurangan stok',
            ]);
        }
        return redirect()->route('produk.index');
    }

    public function destroy(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // Jika request ada 'hapus_stok' (jumlah tertentu), kurangi stok
        if ($request->has('hapus_stok')) {
            $hapus = (int) $request->input('hapus_stok');
            $change = -min($hapus, $produk->stok);
            $produk->stok = max(0, $produk->stok + $change);
            $produk->save();
            if ($change !== 0) {
                ProdukStockLog::create([
                    'produk_id' => $produk->id,
                    'change' => $change,
                    'type' => 'out',
                    'description' => 'Pengurangan stok parsial',
                ]);
            }
        } else {
            // hapus seluruh stock / produk
            if ($produk->stok) {
                ProdukStockLog::create([
                    'produk_id' => $produk->id,
                    'change' => -$produk->stok,
                    'type' => 'out',
                    'description' => 'Penghapusan produk',
                ]);
            }
            $produk->delete();
        }

        return redirect()->route('produk.index');
    }
}
