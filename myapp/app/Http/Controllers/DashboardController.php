<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukStockLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $range = $request->input('range', 'today');
        $month = $request->input('month');

        [$start, $end, $label] = $this->resolveRange($range, $month);

        // Get kategori totals - ensure proper numeric casting
        $kategoriTotals = Produk::query()
            ->select('kategori', DB::raw('SUM(stok) as total_stok'), DB::raw('COUNT(*) as total_produk'))
            ->groupBy('kategori')
            ->orderByDesc('total_stok')
            ->get()
            ->map(function ($item) {
                return [
                    'kategori' => $item->kategori,
                    'total_stok' => (int) $item->total_stok, // Ensure integer
                    'total_produk' => (int) $item->total_produk, // Ensure integer
                ];
            });

        // Stock In: sum of positive changes
        $stockIn = ProdukStockLog::query()
            ->where('change', '>', 0)
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->sum('change') ?? 0;

        // Stock Out: sum of absolute value of negative changes (includes deletions)
        // This includes both stock decreases and product deletions
        $stockOut = ProdukStockLog::query()
            ->where('change', '<', 0)
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->sum(DB::raw('ABS(`change`)')) ?? 0;

        return Inertia::render('Dashboard', [
            'kategoriTotals' => $kategoriTotals,
            'stockFlow' => [
                'in' => (int) $stockIn,
                'out' => (int) $stockOut,
            ],
            'range' => $range,
            'range_label' => $label,
            'range_start' => $start?->toDateString(),
            'range_end' => $end?->toDateString(),
            'availableMonths' => $this->availableMonths(),
        ]);
    }

    private function resolveRange(string $range, ?string $month): array
    {
        $today = Carbon::today();
        return match ($range) {
            'week' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek(), 'Minggu ini'],
            'month' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth(), 'Bulan ini'],
            'custom-month' => $this->monthRange($month, $today),
            default => [$today, $today->copy()->endOfDay(), 'Hari ini'],
        };
    }

    private function monthRange(?string $month, Carbon $fallback): array
    {
        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            return [$start, $end, $start->format('F Y')];
        }

        return [$fallback->copy()->startOfMonth(), $fallback->copy()->endOfMonth(), 'Bulan ini'];
    }

    private function availableMonths(): array
    {
        // Get first log entry or first product creation date
        $firstLog = ProdukStockLog::query()->oldest('created_at')->value('created_at');
        $firstProduct = Produk::query()->oldest('created_at')->value('created_at');

        $first = $firstLog && $firstProduct
            ? ($firstLog < $firstProduct ? $firstLog : $firstProduct)
            : ($firstLog ?? $firstProduct);

        if (!$first) {
            return [];
        }

        $start = Carbon::parse($first)->startOfMonth();
        $end = Carbon::today()->endOfMonth();

        $months = [];
        $cursor = $start->copy();
        while ($cursor->lessThanOrEqualTo($end)) {
            $months[] = [
                'value' => $cursor->format('Y-m'),
                'label' => $cursor->format('F Y'),
            ];
            $cursor->addMonth();
        }

        return $months;
    }
}

