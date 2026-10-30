<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display list of all orders
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start query with relationships
        $query = Order::with(['user', 'orderItems']);

        // Filter by status if selected
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number or customer name
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'LIKE', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'LIKE', '%' . $request->search . '%');
                  });
            });
        }

        // Paginate results
        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display single order details
     * 
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Load all necessary relationships
        $order->load(['user', 'orderItems.product.category']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     * 
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validate status
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // If cancelling order, return stock
        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            // Tambahkan baris ini agar relasi produk diambil sekaligus di awal
            $order->load('orderItems.product'); 
            
            foreach ($order->orderItems as $item) {
                $item->product->increaseStock($item->quantity);
            }
        }

        // Update order status
        $order->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Display sales history report
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function salesHistory(Request $request)
    {
        // Default to current month if no dates specified
        $dateFrom = $request->date_from ?? date('Y-m-01');
        $dateTo = $request->date_to ?? date('Y-m-t');

        // Get completed orders in date range
        $orders = Order::with(['user', 'orderItems.product'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->latest()
            ->get();

        // Calculate summary statistics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
        $totalItemsSold = $orders->sum(function($order) {
            return $order->orderItems->sum('quantity');
        });

        // Group sales by product
        $productSales = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Daily sales breakdown
        $dailySales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.orders.sales-history', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'totalItemsSold',
            'productSales',
            'dailySales',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Delete order (admin can delete cancelled orders)
     * 
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return redirect()->back()
                ->with('error', 'Hanya pesanan yang dibatalkan yang dapat dihapus!');
        }

        // Delete order (cascade will handle order items)
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }
    public function updateShippingCost(Request $request, Order $order)
    {
        $validated = $request->validate([
            'shipping_cost' => 'required|numeric|min:0'
        ]);

        // Hitung total harga barang saja
        $productCost = $order->orderItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // Update database
        $order->shipping_cost = $validated['shipping_cost'];
        $order->total_amount = $productCost + $order->shipping_cost; // Hitung Total Akhir
        $order->save();

        return redirect()->back()->with('success', 'Ongkir berhasil diinput dan total tagihan telah diperbarui!');
    }
}