<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $cartItems = Cart::with(['product.category'])
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        foreach ($cartItems as $item) {
            if (!$item->isAvailable()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Beberapa produk di keranjang tidak tersedia atau stok habis.');
            }
        }

        $totalPrice = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.checkout', compact('cartItems', 'totalPrice'));
    }

    /**
     * Process checkout and create order
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_notes' => 'nullable|string|max:500',
            'shipping_method' => 'required|string|in:Pick-up,Kurir Lokal,Ekspedisi',
            'payment_method' => 'required|string|in:Transfer,COD',
        ]);

        // 🛡️ CEK ALAMAT KOSONG (KEAMANAN BACKEND)
        if (empty(Auth::user()->address)) {
            return redirect()->back()->with('error', 'Silakan isi alamat pengiriman di halaman Profil terlebih dahulu sebelum memesan.');
        }

        $cartItems = Cart::with('product')
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        DB::beginTransaction();

        try {
            $totalAmount = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_method' => $validated['shipping_method'],
                'payment_method' => $validated['payment_method'],
                'shipping_cost' => $validated['shipping_method'] === 'Pick-up' ? 0 : null,
                'customer_notes' => $validated['customer_notes'],
            ]);

            foreach ($cartItems as $cartItem) {
                // Panggil fungsi decrement yang baru
                $stockDecreased = $cartItem->product->decreaseStock($cartItem->quantity);

                // Jika gagal (stok sudah habis diambil orang lain), batalkan semuanya!
                if (!$stockDecreased) {
                    throw new \Exception('Maaf, stok ' . $cartItem->product->name . ' baru saja habis dibeli orang lain. Silakan kurangi jumlahnya.');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'size' => $cartItem->size,
                ]);
            }

            Cart::where('user_id', Auth::id())->delete();
            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Nomor pesanan: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}