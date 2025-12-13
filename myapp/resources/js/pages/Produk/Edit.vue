<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

defineProps({ produk: Object })

const form = useForm({
  nama_produk: produk.nama_produk,
  harga: produk.harga,
  stok: produk.stok,
  kategori:''
})

const submit = () => form.put(`/produk/${produk.id}`)
</script>

<template>
  <AppLayout>
    <div class="max-w-md mx-auto bg-white rounded-2xl shadow p-8">
      <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">Edit Produk</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <input v-model="form.nama_produk" class="w-full rounded-lg border-gray-300" />
        <input v-model="form.harga" type="number" class="w-full rounded-lg border-gray-300" />
        <input v-model="form.stok" type="number" class="w-full rounded-lg border-gray-300" />

        <div class="flex justify-between pt-4">
          <Link href="/produk" class="text-blue-600">← Kembali</Link>
          <button class="bg-blue-600 text-white px-6 py-2 rounded-lg">Update</button>
        </div>
      </form>
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

  </AppLayout>
</template>
