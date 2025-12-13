<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({ produk: Array })

const hapusStock = (id, stok) => {
  const jumlah = prompt(`Jumlah stock yang ingin dihapus? (Max: ${stok}, kosong = hapus semua)`)
  if (jumlah !== null) {
    const data = jumlah.trim() ? { hapus_stok: jumlah } : {}
    router.delete(`/produk/${id}`, { data })
  }
}
</script>

<template>
  <AppLayout>
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-black">Manajemen Produk</h2>
        <Link href="/produk/create" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">+ Tambah Produk</Link>
      </div>
    <div class="mb-4">
        <input
            v-model="searchTerm"
            type="text"
            placeholder="Cari nama barang..."
            class="w-full md:w-1/3 rounded-lg border border-blue-300 px-4 py-2
                text-black placeholder-gray-500
                focus:border-blue-600 focus:ring focus:ring-blue-200"
        />
    </div>

      <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-black">
          <thead class="bg-blue-600 text-white">
            <tr>
                <th class="p-4">ID</th>
                <th class="p-4 text-left">Nama</th>
                <th class="p-4 text-left">Kategori</th>
                <th class="p-4 text-left">Harga Satuan</th>
                <th class="p-4 text-left">Total Stok</th>
                <th class="p-4 text-left">Harga Total</th> <!-- Kolom baru -->
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>

          <tbody>
            <tr v-for="p in produk" :key="p.id" class="border-b hover:bg-blue-50">
                <td class="p-4">{{ p.id }}</td>
                <td class="p-4">{{ p.nama_produk }}</td>
                <td class="p-4">{{ p.kategori }}</td>
                <td class="p-4">Rp {{ p.harga.toLocaleString() }}</td>
                <td class="p-4">{{ p.stok }}</td>
                <td class="p-4">Rp {{ (p.harga * p.stok).toLocaleString() }}</td> <!-- Harga Total -->
                <td class="p-4 text-center space-x-3">
                <Link :href="`/produk/${p.id}/edit`" class="text-blue-600">Edit</Link>
                <button @click="hapusStock(p.id, p.stok)" class="text-red-500">Hapus / Kurangi Stok</button>
                </td>
            </tr>
            <tr v-if="produk.length === 0">
                <td colspan="7" class="p-6 text-center text-gray-500">Data kosong</td>
            </tr>
            </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
