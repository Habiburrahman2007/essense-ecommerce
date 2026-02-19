<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $revenue = Order::whereIn('status', ['paid', 'shipped', 'completed'])->sum('total_price');
        $newOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        return [
            Stat::make('Total Revenue', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description('Revenue from paid orders')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Dummy chart for visual

            Stat::make('New Orders (Pending)', $newOrders)
                ->description('Orders needing attention')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Products', $totalProducts)
                ->description('Active products in store')
                ->descriptionIcon('heroicon-m-archive-box'),
                
            Stat::make('Total Customers', $totalUsers)
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users'),
        ];
    }
}
