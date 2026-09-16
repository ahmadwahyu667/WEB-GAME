<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::whereIn('status', ['paid', 'processing', 'completed'])->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        $pendingPayment = Order::where('status', 'waiting_payment')->count();
        $paidOrders = Order::where('status', 'paid')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $salesPerDay = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->whereIn('status', ['paid', 'processing', 'completed'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        $recentOrders = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $statusDistribution = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->with('game')
            ->take(10)
            ->get();

        $recentPayments = Payment::with('order')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'pendingPayment',
            'paidOrders',
            'completedOrders',
            'salesPerDay',
            'topProducts',
            'recentOrders',
            'statusDistribution',
            'lowStockProducts',
            'recentPayments'
        ));
    }
}
