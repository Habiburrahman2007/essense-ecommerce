<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularCategoriesChart extends ChartWidget
{
    protected ?string $heading = 'Popular Categories';

    protected ?string $description = 'Child categories by total units sold';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        // Ambil child categories (parent_id NOT NULL) terpopuler berdasarkan jumlah item terjual
        $categories = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->whereIn('orders.status', ['paid', 'shipped', 'completed'])
            ->whereNotNull('categories.parent_id') // hanya child categories
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(6)
            ->get();

        $labels = $categories->pluck('name')->toArray();
        $data   = $categories->pluck('total_sold')->map(fn ($v) => (int) $v)->toArray();

        // Palet warna yang serasi dengan tema Essence
        $colors = [
            '#B89F8F', // clay
            '#D9D2C5', // taupe
            '#4A4A4A', // charcoal
            '#C8B5A5', // clay muda
            '#8B7355', // coklat tua
            '#A0926E', // clay gelap
        ];

        return [
            'datasets' => [
                [
                    'data'            => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor'     => '#FDFCF0',
                    'borderWidth'     => 2,
                    'hoverOffset'     => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => [
                        'padding'   => 16,
                        'boxWidth'  => 12,
                        'boxHeight' => 12,
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        // label sudah otomatis dari Chart.js
                    ],
                ],
            ],
            'maintainAspectRatio' => true,
        ];
    }
}
