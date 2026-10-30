<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Message;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics
     * Shows overview of orders, revenue, products, and messages
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Total revenue from completed orders
        $totalRevenue = Order::where('status', 'completed')
                            ->sum('total_amount');

        // Count statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $unreadMessages = Message::where('is_read', false)->count();
        $unansweredMessages = Message::whereNull('admin_reply')->count();

        // Recent orders (last 5)
        $recentOrders = Order::with(['user', 'orderItems'])
                            ->latest()
                            ->limit(5)
                            ->get();

        // Top selling products (last 30 days)
        $topSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Daily revenue for last 7 days (for chart)
        $dailyRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly revenue (current year)
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalProducts',
            'activeProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalCustomers',
            'unreadMessages',
            'unansweredMessages',
            'recentOrders',
            'topSellingProducts',
            'dailyRevenue',
            'monthlyRevenue'
        ));
    }
}