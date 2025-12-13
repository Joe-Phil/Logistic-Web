<template>
  <div class="min-h-screen bg-white text-black font-sans flex flex-col">
    
    <!-- Navbar / Header -->
    <header class="bg-blue-600 text-white shadow-md">
      <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
        <h1 class="text-xl font-bold">Logistic Web</h1>
        <nav class="space-x-4">
          <Link href="/" class="hover:underline">Home</Link>
          <Link href="/dashboard" class="hover:underline">Dashboard</Link>
          <Link href="/produk" class="hover:underline">Produk</Link>
        </nav>
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
</script>

<style>
/* Optional: global font settings */
body {
  font-family: 'Inter', sans-serif;
}
</style>
