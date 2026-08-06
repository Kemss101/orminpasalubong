<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesReportController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->query('month', now()->format('Y-m'));

        [$year, $monthNumber] = explode('-', $month);

        $salesQuery = Sale::query()
            ->whereYear('created_at', (int) $year)
            ->whereMonth('created_at', (int) $monthNumber);

        $totalRevenue = (float) (clone $salesQuery)->sum('total_amount');
        $totalTransactions = (int) (clone $salesQuery)->count();

        $dailySales = (clone $salesQuery)
            ->selectRaw('DATE(created_at) as sale_date, COUNT(*) as transactions, SUM(total_amount) as revenue')
            ->groupBy('sale_date')
            ->orderByDesc('sale_date')
            ->get();

        $pendingOrders = Order::query()->where('status', 'Pending')->count();
        $acceptedOrders = Order::query()->where('status', 'Accepted')->count();
        $declinedOrders = Order::query()->where('status', 'Declined')->count();

        return view('admin.reports.index', compact(
            'month',
            'totalRevenue',
            'totalTransactions',
            'dailySales',
            'pendingOrders',
            'acceptedOrders',
            'declinedOrders'
        ));
    }
}
