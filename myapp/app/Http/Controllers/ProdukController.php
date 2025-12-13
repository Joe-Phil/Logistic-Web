<?php

namespace App\Http\Controllers;

use App\Models\Produk;
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

        Produk::create($request->all());
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
        $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'kategori' => 'required|string',
        ]);

        $produk->update($request->all());
        return redirect()->route('produk.index');
    }

    public function destroy(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // Jika request ada 'hapus_stok' (jumlah tertentu), kurangi stok
        if ($request->has('hapus_stok')) {
            $hapus = (int) $request->input('hapus_stok');
            $produk->stok = max(0, $produk->stok - $hapus);
            $produk->save();
        } else {
            // hapus seluruh stock / produk
            $produk->delete();
        }

        return redirect()->route('produk.index');
    }
}
