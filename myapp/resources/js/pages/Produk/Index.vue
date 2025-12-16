<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/components/ui/dialog'

const { produk, search } = defineProps({
  produk: { type: Array, default: () => [] },
  search: { type: String, default: '' },
  sort_by: { type: String, default: 'id' },
  sort_dir: { type: String, default: 'asc' },
})

const isModalOpen = ref(false)
const isEditModalOpen = ref(false)
const searchTerm = ref(search || '')

let searchTimer
watch(searchTerm, (value) => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    router.get('/produk', { search: value }, { preserveState: true, replace: true, preserveScroll: true })
  }, 300)
})

const form = useForm({
  nama_produk: '',
  harga: '',
  stok: '',
  kategori: ''
})

const editForm = useForm({
  id: null,
  nama_produk: '',
  harga: '',
  stok: '',
  kategori: ''
})

const deleteLoading = ref(false)

const submit = () => {
  form.post('/produk', {
    onSuccess: () => {
      form.reset()
      isModalOpen.value = false
    }
  })
}

const submitEdit = () => {
  if (!editForm.id) return
  editForm.put(`/produk/${editForm.id}`, {
    onSuccess: () => {
      editForm.reset()
      editForm.clearErrors()
      isEditModalOpen.value = false
    }
  })
}

const openEditModal = (produk) => {
  editForm.id = produk.id
  editForm.nama_produk = produk.nama_produk ?? ''
  editForm.kategori = produk.kategori ?? ''
  editForm.harga = produk.harga ?? ''
  editForm.stok = produk.stok ?? ''
  editForm.clearErrors()
  isEditModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  form.reset()
  form.clearErrors()
}

const closeEditModal = () => {
  isEditModalOpen.value = false
  editForm.reset()
  editForm.clearErrors()
}

const deleteItem = () => {
  if (!editForm.id) return
  if (!confirm('Yakin ingin menghapus produk ini?')) return

  deleteLoading.value = true
  router.delete(`/produk/${editForm.id}`, {
    onSuccess: () => {
      closeEditModal()
    },
    onFinish: () => {
      deleteLoading.value = false
    },
  })
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<template>
  <AppLayout>
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-black">Manajemen Produk</h2>
        <button 
          @click="isModalOpen = true" 
          class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700"
        >
          + Tambah Produk
        </button>
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
                <th class="p-4 text-left">Harga Total</th>
                <th class="p-4 text-left">Last Updated</th>
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
                <td class="p-4">Rp {{ (p.harga * p.stok).toLocaleString() }}</td>
                <td class="p-4">{{ formatDate(p.updated_at) }}</td>
                <td class="p-4 text-center">
                  <button
                    @click="openEditModal(p)"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                  >
                    Edit
                  </button>
                </td>
            </tr>
            <tr v-if="produk.length === 0">
                <td colspan="8" class="p-6 text-center text-gray-500">Data kosong</td>
            </tr>
            </tbody>
        </table>
      </div>

      <!-- Modal Tambah Produk -->
      <Dialog :open="isModalOpen" @update:open="(value) => { isModalOpen = value; if (!value) { form.reset(); form.clearErrors(); } }">
        <DialogContent class="sm:max-w-md">
          <DialogHeader>
            <DialogTitle class="text-2xl font-bold text-black text-center">
              Tambah Produk
            </DialogTitle>
          </DialogHeader>

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
                :class="{ 'border-red-500': form.errors.nama_produk }"
              />
              <p v-if="form.errors.nama_produk" class="text-red-500 text-sm mt-1">
                {{ form.errors.nama_produk }}
              </p>
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
                :class="{ 'border-red-500': form.errors.kategori }"
              />
              <p v-if="form.errors.kategori" class="text-red-500 text-sm mt-1">
                {{ form.errors.kategori }}
              </p>
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
                :class="{ 'border-red-500': form.errors.harga }"
              />
              <p v-if="form.errors.harga" class="text-red-500 text-sm mt-1">
                {{ form.errors.harga }}
              </p>
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
                :class="{ 'border-red-500': form.errors.stok }"
              />
              <p v-if="form.errors.stok" class="text-red-500 text-sm mt-1">
                {{ form.errors.stok }}
              </p>
            </div>

            <!-- Action Buttons -->
            <DialogFooter class="flex justify-between items-center pt-4">
              <button
                type="button"
                @click="closeModal"
                class="text-blue-600 hover:underline"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="form.processing"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg
                       hover:bg-blue-700 transition disabled:opacity-50"
              >
                <span v-if="form.processing">Menyimpan...</span>
                <span v-else>Simpan</span>
              </button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      <!-- Modal Edit Produk -->
      <Dialog :open="isEditModalOpen" @update:open="(value) => { isEditModalOpen = value; if (!value) { editForm.reset(); editForm.clearErrors(); } }">
        <DialogContent class="sm:max-w-md">
          <DialogHeader>
            <DialogTitle class="text-2xl font-bold text-black text-center">
              Edit Produk
            </DialogTitle>
          </DialogHeader>

          <form @submit.prevent="submitEdit" class="space-y-5">
            <!-- Nama Produk -->
            <div>
              <label class="block text-sm font-semibold text-black mb-1">
                Nama Produk
              </label>
              <input
                v-model="editForm.nama_produk"
                type="text"
                class="w-full rounded-lg border border-blue-300 px-4 py-2
                       text-black placeholder-gray-500
                       focus:border-blue-600 focus:ring-blue-600"
                :class="{ 'border-red-500': editForm.errors.nama_produk }"
              />
              <p v-if="editForm.errors.nama_produk" class="text-red-500 text-sm mt-1">
                {{ editForm.errors.nama_produk }}
              </p>
            </div>

            <!-- Kategori -->
            <div>
              <label class="block text-sm font-semibold text-black mb-1">
                Kategori
              </label>
              <input
                v-model="editForm.kategori"
                type="text"
                class="w-full rounded-lg border border-blue-300 px-4 py-2
                       text-black placeholder-gray-500
                       focus:border-blue-600 focus:ring-blue-600"
                :class="{ 'border-red-500': editForm.errors.kategori }"
              />
              <p v-if="editForm.errors.kategori" class="text-red-500 text-sm mt-1">
                {{ editForm.errors.kategori }}
              </p>
            </div>

            <!-- Harga -->
            <div>
              <label class="block text-sm font-semibold text-black mb-1">
                Harga Satuan
              </label>
              <input
                v-model="editForm.harga"
                type="number"
                class="w-full rounded-lg border border-blue-300 px-4 py-2
                       text-black placeholder-gray-500
                       focus:border-blue-600 focus:ring-blue-600"
                :class="{ 'border-red-500': editForm.errors.harga }"
              />
              <p v-if="editForm.errors.harga" class="text-red-500 text-sm mt-1">
                {{ editForm.errors.harga }}
              </p>
            </div>

            <!-- Stok -->
            <div>
              <label class="block text-sm font-semibold text-black mb-1">
                Total Stok
              </label>
              <input
                v-model="editForm.stok"
                type="number"
                class="w-full rounded-lg border border-blue-300 px-4 py-2
                       text-black placeholder-gray-500
                       focus:border-blue-600 focus:ring-blue-600"
                :class="{ 'border-red-500': editForm.errors.stok }"
              />
              <p v-if="editForm.errors.stok" class="text-red-500 text-sm mt-1">
                {{ editForm.errors.stok }}
              </p>
            </div>

            <DialogFooter class="flex justify-between items-center pt-4">
              <button
                type="button"
                @click="closeEditModal"
                class="text-blue-600 hover:underline"
              >
                Batal
              </button>
              <button
                type="button"
                @click="deleteItem"
                :disabled="deleteLoading || editForm.processing"
                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition disabled:opacity-50"
              >
                <span v-if="deleteLoading">Menghapus...</span>
                <span v-else>Hapus</span>
              </button>
              <button
                type="submit"
                :disabled="editForm.processing"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg
                       hover:bg-blue-700 transition disabled:opacity-50"
              >
                <span v-if="editForm.processing">Menyimpan...</span>
                <span v-else>Simpan</span>
              </button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>
