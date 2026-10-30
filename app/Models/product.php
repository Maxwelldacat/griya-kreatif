<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Defines which fields can be filled in bulk operations
     */
    protected $fillable = [
        'category_id', 
        'name', 
        'description', 
        'price', 
        'stock', 
        'pirt_number', 
        'image', 
        'has_sizes', 
        'is_active'  // BENAR
    ];

    /**
     * The attributes that should be cast.
     * Automatically converts database values to appropriate PHP types
     */
    protected $casts = [
        'has_sizes' => 'boolean',  // BENAR
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Product belongs to a category
     * Each product must be in one category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Product has many cart items
     * Same product can be in multiple users' carts
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relationship: Product has many order items
     * Tracks all historical purchases of this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if product is in stock
     * @return bool
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Check if product has sufficient stock for requested quantity
     * @param int $quantity
     * @return bool
     */
    public function hasSufficientStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Decrease product stock securely (Prevents Race Condition)
     * @param int $quantity
     * @return bool
     */
    public function decreaseStock(int $quantity): bool
    {
        // Sistem akan mengeksekusi query:
        // UPDATE products SET stock = stock - $quantity WHERE id = $this->id AND stock >= $quantity
        
        $affectedRows = self::where('id', $this->id)
                            ->where('stock', '>=', $quantity)
                            ->decrement('stock', $quantity);

        // Jika $affectedRows = 0, berarti stok tidak cukup (sudah diambil orang lain)
        return $affectedRows > 0; 
    }

    /**
     * Increase product stock (called when admin adds inventory)
     * @param int $quantity
     * @return void
     */
    public function increaseStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    /**
     * Scope: Get only active products
     * Usage: Product::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get products that are in stock
     * Usage: Product::inStockOnly()->get()
     */
    public function scopeInStockOnly($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Get formatted price with currency
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get image URL or placeholder
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        // Logika inti tidak dihapus. Kita buang pengecekan file_exists yang berat.
        // Kita sudah antisipasi gambar rusak menggunakan atribut `onerror` di tampilan Blade.
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-image.png'); 
    }
}