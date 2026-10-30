<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the customer homepage with products
     * Shows all active products with category filtering and advanced search
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get all categories for filter buttons
        $categories = Category::withActiveProducts()->get();

        // Start query for active products only
        $query = Product::with('category')
                        ->where('is_active', true);

        // Filter by category if selected
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // ADVANCED SEARCH: Mencari berdasarkan Nama Produk, Deskripsi, ATAU Nama Kategori
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($qCat) use ($searchTerm) {
                      $qCat->where('name', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        // Paginate results (15 products per page)
        $products = $query->latest()->paginate(15);

        // Get selected category for highlighting active filter
        $selectedCategory = $request->category;

        return view('customer.home', compact('products', 'categories', 'selectedCategory'));
    }

    /**
     * Display single product details
     * 
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // Ensure product is active
        if (!$product->is_active) {
            abort(404, 'Product not found');
        }

        // Load category relationship
        $product->load('category');

        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->where('is_active', true)
                                  ->inStockOnly()
                                  ->limit(4)
                                  ->get();

        return view('customer.product-detail', compact('product', 'relatedProducts'));
    }
}