<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type KategoriTotal = {
    kategori: string;
    total_stok: number;
    total_produk: number;
};

const props = withDefaults(
    defineProps<{
        kategoriTotals: KategoriTotal[];
        stockFlow: { in: number; out: number };
        range: string;
        range_label: string;
        range_start: string | null;
        range_end: string | null;
        availableMonths: { value: string; label: string }[];
    }>(),
    {
        kategoriTotals: () => [],
        stockFlow: () => ({ in: 0, out: 0 }),
        range: 'today',
        range_label: 'Today',
        range_start: null,
        range_end: null,
        availableMonths: () => [],
    },
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const palette = [
    '#2563eb',
    '#7c3aed',
    '#0ea5e9',
    '#22c55e',
    '#f97316',
    '#e11d48',
    '#f59e0b',
];

const range = ref(props.range ?? 'today');
const selectedMonth = ref(
    props.range === 'custom-month' && props.range_start
        ? props.range_start.slice(0, 7)
        : props.availableMonths[props.availableMonths.length - 1]?.value ?? '',
);

const totalStok = computed(() => {
    const sum = props.kategoriTotals.reduce((acc, item) => {
        const value = Number(item.total_stok) || 0;
        return acc + value;
    }, 0);
    return sum;
});

const donutGradient = computed(() => {
    if (!props.kategoriTotals.length || totalStok.value === 0) {
        return { background: '#eef2ff' };
    }

    let acc = 0;
    const parts: string[] = [];
    props.kategoriTotals.forEach((item, idx) => {
        const stokValue = Number(item.total_stok) || 0;
        const start = (acc / totalStok.value) * 100;
        acc += stokValue;
        const end = (acc / totalStok.value) * 100;
        parts.push(`${palette[idx % palette.length]} ${start}% ${end}%`);
    });

    return { background: `conic-gradient(${parts.join(', ')})` };
});

const donutLegend = computed(() =>
    props.kategoriTotals.map((item, idx) => {
        const stokValue = Number(item.total_stok) || 0;
        const percentage = totalStok.value > 0
            ? Math.round((stokValue / totalStok.value) * 100)
            : 0;
        return {
            ...item,
            total_stok: stokValue,
            color: palette[idx % palette.length],
            percentage: percentage,
        };
    }),
);

const stockBars = computed(() => {
    const masuk = Number(props.stockFlow?.in) || 0;
    const keluar = Number(props.stockFlow?.out) || 0;
    const max = Math.max(masuk, keluar, 1);
    return [
        { label: 'Stok Masuk', value: masuk, color: 'bg-blue-600', width: `${(masuk / max) * 100}%` },
        { label: 'Stok Keluar', value: keluar, color: 'bg-rose-500', width: `${(keluar / max) * 100}%` },
    ];
});

const applyRange = () => {
    const params: Record<string, string> = { range: range.value };
    if (range.value === 'custom-month' && selectedMonth.value) {
        params.month = selectedMonth.value;
    }
    router.get(dashboard(), params, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Hero -->
            <div
                class="relative overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-indigo-50 to-white p-6 text-slate-900 shadow-sm dark:border-slate-700/50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950"
            >
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Dashboard</p>
                        <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">
                            Insight stok dan kategori produk
                        </h1>
                        <p class="mt-1 max-w-3xl text-sm text-slate-600 dark:text-slate-400">
                            Pantau komposisi stok per kategori dan arus masuk/keluar dalam rentang waktu yang Anda pilih.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <div class="rounded-full bg-white/70 px-3 py-2 text-xs font-medium text-slate-700 shadow-sm ring-1 ring-slate-100 backdrop-blur dark:bg-slate-800/70 dark:text-slate-200 dark:ring-slate-700">
                            Periode: {{ props.range_label }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700/50 dark:bg-slate-900">
                <div class="flex flex-wrap items-center gap-2">
                    <button
                        v-for="option in [
                            { label: 'Hari ini', value: 'today' },
                            { label: 'Minggu ini', value: 'week' },
                            { label: 'Bulan ini', value: 'month' },
                            { label: 'Bulan tertentu', value: 'custom-month' },
                        ]"
                        :key="option.value"
                        type="button"
                        @click="range = option.value"
                        class="rounded-lg border px-3 py-2 text-sm font-medium transition"
                        :class="range === option.value
                            ? 'border-blue-600 bg-blue-50 text-blue-700 dark:border-blue-500 dark:bg-blue-500/20 dark:text-blue-100'
                            : 'border-slate-200 text-slate-700 hover:border-blue-200 hover:bg-blue-50 dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-500/50 dark:hover:bg-slate-800'"
                    >
                        {{ option.label }}
                    </button>
                    <div class="flex items-center gap-2" v-if="range === 'custom-month'">
                        <label class="text-sm text-slate-600 dark:text-slate-300">Pilih bulan</label>
                        <select
                            v-model="selectedMonth"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
                        >
                            <option v-for="m in availableMonths" :key="m.value" :value="m.value">
                                {{ m.label }}
                            </option>
                        </select>
                    </div>
                    <button
                        type="button"
                        @click="applyRange"
                        class="ml-auto inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        Terapkan
                    </button>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Menampilkan data dari {{ props.range_start ?? '—' }} sampai {{ props.range_end ?? '—' }}.
                </p>
            </div>

            <!-- Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700/50 dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Stok</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ totalStok.toLocaleString() }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Akumulasi seluruh kategori</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700/50 dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Stok Masuk</p>
                    <p class="mt-2 text-3xl font-semibold text-blue-700 dark:text-blue-300">{{ (stockBars[0]?.value ?? 0).toLocaleString() }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Penambahan stok terlog</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700/50 dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Stok Keluar</p>
                    <p class="mt-2 text-3xl font-semibold text-rose-600 dark:text-rose-300">{{ (stockBars[1]?.value ?? 0).toLocaleString() }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pengurangan stok terlog</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid gap-4 lg:grid-cols-5">
                <!-- Donut -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700/50 dark:bg-slate-900 lg:col-span-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Komposisi Stok per Kategori</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Donut chart + penjelasan angka</p>
                        </div>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-200">Live</span>
                    </div>
                    <div class="mt-6 flex flex-col gap-6 md:flex-row md:items-center">
                        <div class="mx-auto flex h-56 w-56 items-center justify-center rounded-full bg-slate-50 p-6 shadow-inner dark:bg-slate-800">
                            <div class="relative h-full w-full rounded-full" :style="donutGradient">
                                <div class="absolute inset-4 flex flex-col items-center justify-center rounded-full bg-white text-center shadow-sm dark:bg-slate-900">
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Total Stok</span>
                                    <span class="text-2xl font-semibold text-slate-900 dark:text-white">{{ totalStok.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div
                                v-for="item in donutLegend"
                                :key="item.kategori"
                                class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50/80 px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-800/50"
                            >
                                <div class="flex items-center gap-3">
                                    <span class="h-3 w-3 rounded-full" :style="{ backgroundColor: item.color }"></span>
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ item.kategori || 'Tidak ada kategori' }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.total_produk }} produk</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ item.total_stok.toLocaleString() }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.percentage }}%</p>
                                </div>
                            </div>
                            <p v-if="!donutLegend.length" class="text-sm text-slate-500 dark:text-slate-400">
                                Belum ada data stok untuk ditampilkan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bar chart -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700/50 dark:bg-slate-900 lg:col-span-2">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Arus Stok (Masuk vs Keluar)</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Horizontal bar chart</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            {{ props.range_label }}
                        </span>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div v-for="bar in stockBars" :key="bar.label" class="space-y-1">
                            <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                                <span>{{ bar.label }}</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ bar.value.toLocaleString() }}</span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                                <div class="h-3 rounded-full" :class="bar.color" :style="{ width: bar.width }"></div>
                            </div>
                        </div>
                        <p v-if="!stockBars.length" class="text-sm text-slate-500 dark:text-slate-400">
                            Belum ada data pergerakan stok.
                        </p>
                        <div class="mt-4 rounded-lg bg-slate-50 p-3 text-xs text-slate-600 shadow-sm dark:bg-slate-800 dark:text-slate-300">
                            Data diambil dari log stok (tambah/kurang) dalam rentang tanggal yang dipilih.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
