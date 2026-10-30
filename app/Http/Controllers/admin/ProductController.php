<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display list of all products
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start query with category relationship
        $query = Product::with('category');

        // Filter by category if selected
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by status (active/inactive)
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status);
        }

        // Search by product name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Paginate results
        $products = $query->latest()->paginate(20);

        // Get all categories for filter dropdown
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show form to create new product
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all categories for dropdown
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store new product in database
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'pirt_number' => 'nullable|string|max:100',
            'has_sizes' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Convert checkbox values (if not checked, they won't be in request)
        $validated['has_sizes'] = $request->has('has_sizes') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Create product
        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display single product details
     * 
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // Load category
        $product->load(['category']);

        // Calculate total sold
        $totalSold = $product->orderItems()
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');

        return view('admin.products.show', compact('product', 'totalSold'));
    }

    /**
     * Show form to edit product
     * 
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        // Get all categories for dropdown
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product in database
     * 
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        // Validate input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pirt_number' => 'nullable|string|max:100',
            'has_sizes' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Convert checkbox values
        $validated['has_sizes'] = $request->has('has_sizes') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Update product
        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Delete product from database
     * 
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // Delete product image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete product (cascade will handle cart and order items)
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Quick update stock (AJAX endpoint)
     * 
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStock(Request $request, Product $product)
    {
        // Validate stock change
        $validated = $request->validate([
            'action' => 'required|in:increase,decrease,set',
            'amount' => 'required|integer|min:0',
        ]);

        // Apply stock change
        switch ($validated['action']) {
            case 'increase':
                $product->increaseStock($validated['amount']);
                break;
            case 'decrease':
                if ($product->stock >= $validated['amount']) {
                    $product->decreaseStock($validated['amount']);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk dikurangi.'
                    ], 400);
                }
                break;
            case 'set':
                $product->update(['stock' => $validated['amount']]);
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui!',
            'new_stock' => $product->fresh()->stock
        ]);
    }
}