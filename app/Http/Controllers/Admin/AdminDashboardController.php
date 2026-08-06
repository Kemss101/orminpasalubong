<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::query()->count();
        $pendingOrders = Order::query()->where('status', 'Pending')->count();
        $salesToday = (float) Sale::query()->whereDate('created_at', now()->toDateString())->sum('total_amount');
        $lowStockAlerts = Product::query()
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->count();
        $managementHistory = Product::query()
            ->latest('updated_at')
            ->limit(10)
            ->get([
                'id',
                'sku_code',
                'name',
                'price',
                'stock_quantity',
                'low_stock_threshold',
                'image_path',
                'created_at',
                'updated_at',
            ]);

        return view('dashboards.admin', compact(
            'totalProducts',
            'pendingOrders',
            'salesToday',
            'lowStockAlerts',
            'managementHistory'
        ));
    }
}
