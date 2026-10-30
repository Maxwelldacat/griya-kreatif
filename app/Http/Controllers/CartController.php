<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display user's shopping cart
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get cart items with product and category relationships
        $cartItems = Cart::with(['product.category'])
                        ->where('user_id', Auth::id())
                        ->get();

        // Calculate total price
        $totalPrice = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.cart', compact('cartItems', 'totalPrice'));
    }

    /**
     * Add product to cart
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|in:S,M,L,XL', // Size required only for clothing
        ]);

        // Get product
        $product = Product::findOrFail($validated['product_id']);

        // Check if product is active
        if (!$product->is_active) {
            return redirect()->back()->with('error', 'Produk tidak tersedia.');
        }

        // Check stock availability
        if (!$product->hasSufficientStock($validated['quantity'])) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Tersedia: ' . $product->stock);
        }

        // Check if product requires size selection
        if ($product->has_sizes && !$request->size) {
            return redirect()->back()->with('error', 'Silakan pilih ukuran terlebih dahulu.');
        }

        // Check if item already exists in cart (same product + size)
        $existingCart = Cart::where('user_id', Auth::id())
                           ->where('product_id', $validated['product_id'])
                           ->where('size', $validated['size'] ?? null)
                           ->first();

        if ($existingCart) {
            // ... logika update kuantitas ...
} else {
    Cart::create([
        'user_id' => Auth::id(),
        'product_id' => $validated['product_id'],
        'quantity' => $validated['quantity'],
        'size' => $validated['size'] ?? null, // <--- Pastikan ada ?? null di sini juga
    ]);
        }
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update cart item quantity
     * 
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cart $cart)
    {
        // Ensure cart belongs to authenticated user
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate quantity
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check stock availability
        if (!$cart->product->hasSufficientStock($validated['quantity'])) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Tersedia: ' . $cart->product->stock);
        }

        // Update cart quantity
        $cart->update(['quantity' => $validated['quantity']]);

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Remove item from cart
     * 
     * @param Cart $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cart $cart)
    {
        // Ensure cart belongs to authenticated user
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete cart item
        $cart->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    /**
     * Clear all items from cart
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        // Delete all cart items for authenticated user
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}