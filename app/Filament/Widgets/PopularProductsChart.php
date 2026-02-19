<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularProductsChart extends ChartWidget
{
    protected ?string $heading = 'Best Selling Products';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get top 5 products by quantity sold
        $products = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id') // Correct join to get product name
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->whereIn('orders.status', ['paid', 'shipped', 'completed'])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Units Sold',
                    'data' => $products->pluck('total_sold')->toArray(),
                    'backgroundColor' => '#d97706', // Amber-600
                    'borderColor' => '#b45309', // Amber-700
                ],
            ],
            'labels' => $products->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
