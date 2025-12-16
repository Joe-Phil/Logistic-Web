<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 p-6 text-slate-900 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800 dark:text-slate-50"
    >
        <!-- Soft blobs -->
        <div class="pointer-events-none absolute inset-0 opacity-60 blur-3xl">
            <div class="absolute left-10 top-10 h-48 w-48 rounded-full bg-blue-200/50 dark:bg-blue-900/40" />
            <div class="absolute right-16 top-24 h-56 w-56 rounded-full bg-indigo-200/50 dark:bg-indigo-900/40" />
            <div class="absolute bottom-10 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-purple-200/40 dark:bg-purple-900/30" />
        </div>

        <div class="relative w-full max-w-4xl">
            <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                <!-- Intro panel -->
                <div class="hidden rounded-3xl bg-white/70 p-10 shadow-xl ring-1 ring-white/60 backdrop-blur dark:bg-slate-900/70 dark:ring-white/10 lg:block">
                    <div class="mb-6 inline-flex items-center gap-3 rounded-full bg-white/70 px-4 py-2 text-xs font-semibold text-blue-700 ring-1 ring-blue-100 backdrop-blur dark:bg-slate-800/80 dark:text-blue-200 dark:ring-blue-900/50">
                        Logistic Web
                    </div>
                    <h1 class="text-4xl font-extrabold leading-tight tracking-tight">
                        <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Kelola logistik dengan tenang
                        </span>
                    </h1>
                    <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">
                        Akses aman, cepat, dan modern. Atur stok, pantau pergerakan, dan nikmati pengalaman yang konsisten dengan halaman utama.
                    </p>
                    <div class="mt-6 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-500" /> Tampilan selaras dengan landing page
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-indigo-500" /> Fokus pada kemudahan login dan registrasi
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-purple-500" /> Siap untuk produksi
                        </div>
                    </div>
                </div>

                <!-- Form card -->
                <div class="relative">
                    <div class="absolute inset-0 rounded-3xl bg-white/40 blur-xl ring-1 ring-white/50 dark:bg-slate-900/40 dark:ring-white/5" />
                    <div class="relative rounded-3xl border border-white/60 bg-white/85 p-8 shadow-2xl backdrop-blur-xl transition hover:shadow-[0_25px_80px_-25px_rgba(59,130,246,0.35)] dark:border-white/10 dark:bg-slate-900/80">
                        <Link
                            :href="home()"
                            class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-blue-700 transition hover:text-blue-900 dark:text-blue-200 dark:hover:text-blue-100"
                        >
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600">
                                <AppLogoIcon class="h-6 w-6 fill-white" />
                            </div>
                            Kembali ke beranda
                        </Link>

                        <div class="space-y-2 text-left">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-300">
                                Logistic Web
                            </p>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">
                                {{ title }}
                            </h2>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ description }}
                            </p>
                        </div>

                        <div class="mt-8">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
