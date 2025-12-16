<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { Package, BarChart3, Shield, Zap, ArrowRight, Check } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const features = [
    {
        icon: Package,
        title: 'Manajemen Produk',
        description: 'Kelola inventori produk Anda dengan mudah dan efisien',
    },
    {
        icon: BarChart3,
        title: 'Analitik Real-time',
        description: 'Pantau statistik stok dan kategori dengan dashboard interaktif',
    },
    {
        icon: Shield,
        title: 'Aman & Terpercaya',
        description: 'Data Anda terlindungi dengan sistem keamanan terbaik',
    },
    {
        icon: Zap,
        title: 'Cepat & Responsif',
        description: 'Akses cepat dan pengalaman pengguna yang mulus',
    },
];

const isVisible = ref(false);

onMounted(() => {
    setTimeout(() => {
        isVisible.value = true;
    }, 100);
});
</script>

<template>
    <Head title="Welcome - Logistic Web">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800">
        <!-- Navigation -->
        <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/80 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/80">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
                <div class="flex items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600">
                        <Package class="h-6 w-6 text-white" />
                    </div>
                    <span class="text-xl font-bold text-slate-900 dark:text-white">Logistic Web</span>
                </div>
                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        Dashboard
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white"
                        >
                            Masuk
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            Daftar
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="mx-auto max-w-7xl px-6 pt-20 pb-32 lg:px-8">
            <div
                class="mx-auto max-w-4xl text-center"
                :class="{ 'opacity-0 translate-y-4': !isVisible, 'opacity-100 translate-y-0': isVisible }"
                style="transition: opacity 0.8s ease-out, transform 0.8s ease-out;"
            >
                <!-- Gradient Text Heading -->
                <h1 class="text-5xl font-extrabold tracking-tight sm:text-6xl lg:text-7xl">
                    <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Selamat Datang
                    </span>
                    <br />
                    <span class="text-slate-900 dark:text-white">di Logistic Web</span>
                </h1>

                <p class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-300 sm:text-xl">
                    Solusi lengkap untuk manajemen inventori dan logistik bisnis Anda.
                    <br />
                    Kelola produk, pantau stok, dan analisis data dengan mudah.
                </p>

                <!-- CTA Buttons -->
                <div class="mt-10 flex items-center justify-center gap-4">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="canRegister ? register() : login()"
                        class="group inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg transition hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl"
                    >
                        Mulai Sekarang
                        <ArrowRight class="h-5 w-5 transition group-hover:translate-x-1" />
                    </Link>
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="login()"
                        class="rounded-lg border-2 border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-900 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:border-slate-600 dark:hover:bg-slate-700"
                    >
                        Masuk
                    </Link>
                    <Link
                        v-else
                        :href="dashboard()"
                        class="group inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg transition hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl"
                    >
                        Buka Dashboard
                        <ArrowRight class="h-5 w-5 transition group-hover:translate-x-1" />
                    </Link>
                </div>
            </div>

            <!-- Features Grid -->
            <div
                class="mx-auto mt-32 max-w-6xl"
                :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
                style="transition: opacity 1s ease-out 0.2s, transform 1s ease-out 0.2s; transition-delay: 0.2s;"
            >
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">
                        Fitur Unggulan
                    </h2>
                    <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">
                        Semua yang Anda butuhkan untuk mengelola logistik dengan efisien
                    </p>
                </div>

                <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        v-for="(feature, index) in features"
                        :key="feature.title"
                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-blue-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-700"
                        :style="`transition-delay: ${index * 0.1}s;`"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 opacity-0 transition group-hover:opacity-100 dark:from-blue-950/20 dark:to-indigo-950/20" />
                        <div class="relative">
                            <div class="mb-4 inline-flex rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 p-3">
                                <component :is="feature.icon" class="h-6 w-6 text-white" />
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                {{ feature.title }}
                            </h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                {{ feature.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div
                class="mx-auto mt-32 max-w-6xl rounded-3xl border border-slate-200 bg-gradient-to-br from-blue-600 to-indigo-600 p-12 shadow-2xl dark:border-slate-800"
                :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
                style="transition: opacity 1s ease-out 0.4s, transform 1s ease-out 0.4s; transition-delay: 0.4s;"
            >
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white sm:text-4xl">
                        Kelola Logistik dengan Percaya Diri
                    </h2>
                    <p class="mt-4 text-lg text-blue-100">
                        Platform terpercaya untuk manajemen inventori modern
                    </p>
                </div>

                <div class="mt-12 grid gap-8 sm:grid-cols-3">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">100%</div>
                        <div class="mt-2 text-blue-100">Aman & Terpercaya</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">24/7</div>
                        <div class="mt-2 text-blue-100">Akses Kapan Saja</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">Real-time</div>
                        <div class="mt-2 text-blue-100">Update Instan</div>
                    </div>
                </div>
            </div>

            <!-- Final CTA -->
            <div
                class="mx-auto mt-32 max-w-4xl text-center"
                :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
                style="transition: opacity 1s ease-out 0.6s, transform 1s ease-out 0.6s; transition-delay: 0.6s;"
            >
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">
                    Siap Memulai?
                </h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">
                    Bergabunglah sekarang dan tingkatkan efisiensi manajemen logistik Anda
                </p>
                <div class="mt-8">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="canRegister ? register() : login()"
                        class="group inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl"
                    >
                        Daftar Gratis
                        <ArrowRight class="h-5 w-5 transition group-hover:translate-x-1" />
                    </Link>
                    <Link
                        v-else
                        :href="dashboard()"
                        class="group inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl"
                    >
                        Masuk ke Dashboard
                        <ArrowRight class="h-5 w-5 transition group-hover:translate-x-1" />
                    </Link>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white/50 py-8 dark:border-slate-800 dark:bg-slate-900/50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600">
                            <Package class="h-5 w-5 text-white" />
                        </div>
                        <span class="font-semibold text-slate-900 dark:text-white">Logistic Web</span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        © 2025 Logistic Web. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
