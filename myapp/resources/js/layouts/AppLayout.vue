<template>
  <div class="min-h-screen bg-white text-black font-sans flex flex-col">

    <!-- Navbar / Header -->
    <header class="bg-blue-600 text-white shadow-md">
      <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
        <h1 class="text-xl font-bold">Logistic Web</h1>
        <div class="flex items-center space-x-4">
          <nav class="space-x-4">
            <Link href="/" class="hover:underline">Home</Link>
            <Link href="/dashboard" class="hover:underline">Dashboard</Link>
            <Link href="/produk" class="hover:underline">Produk</Link>
          </nav>
          <Link
            :href="logout()"
            method="post"
            as="button"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 cursor-pointer"
          >
            Logout
          </Link>
        </div>
      </div>
    </header>

    <!-- Main content -->
    <main class="flex-1 max-w-6xl mx-auto w-full p-6">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-blue-50 text-gray-600 text-center py-4 mt-auto border-t">
      &copy; 2025 Logistic Web. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { logout } from '@/routes'
</script>

<style>
/* Optional: global font settings */
body {
  font-family: 'Inter', sans-serif;
}
</style>
