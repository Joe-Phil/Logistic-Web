<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  nama_produk: '',
  harga: '',
  stok: '',
  kategori: ''
})

const submit = () => form.post('/produk')
</script>

<template>
  <AppLayout>
    <div class="max-w-md mx-auto bg-white rounded-2xl shadow p-8">
      <h2 class="text-2xl font-bold text-black mb-6 text-center">
        Tambah Produk
      </h2>

      <form @submit.prevent="submit" class="space-y-5">

        <!-- Nama Produk -->
        <div>
          <label class="block text-sm font-semibold text-black mb-1">
            Nama Produk
          </label>
          <input
            v-model="form.nama_produk"
            type="text"
            placeholder="Contoh: Laptop ASUS"
            class="w-full rounded-lg border border-blue-300 px-4 py-2
                   text-black placeholder-gray-500
                   focus:border-blue-600 focus:ring-blue-600"
          />
        </div>

        <!-- Harga -->
        <div>
          <label class="block text-sm font-semibold text-black mb-1">
            Harga
          </label>
          <input
            v-model="form.harga"
            type="number"
            placeholder="15000000"
            class="w-full rounded-lg border border-blue-300 px-4 py-2
                   text-black placeholder-gray-500
                   focus:border-blue-600 focus:ring-blue-600"
          />
        </div>

        <!-- Stok -->
        <div>
          <label class="block text-sm font-semibold text-black mb-1">
            Stok
          </label>
          <input
            v-model="form.stok"
            type="number"
            placeholder="10"
            class="w-full rounded-lg border border-blue-300 px-4 py-2
                   text-black placeholder-gray-500
                   focus:border-blue-600 focus:ring-blue-600"
          />
        </div>

        <!-- Kategori -->
        <div>
          <label class="block text-sm font-semibold text-black mb-1">
            Kategori
          </label>
          <input
            v-model="form.kategori"
            type="text"
            placeholder="Contoh: Laptop"
            class="w-full rounded-lg border border-blue-300 px-4 py-2
                text-black placeholder-gray-500
                focus:border-blue-600 focus:ring-blue-600"
          />
        </div>

        <!-- Action -->
        <div class="flex justify-between items-center pt-4">
          <Link href="/produk" class="text-blue-600 hover:underline">
            ← Kembali
          </Link>

          <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg
                   hover:bg-blue-700 transition"
          >
            Simpan
          </button>
        </div>

      </form>
    </div>
  </AppLayout>
</template>
