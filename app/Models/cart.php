<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Allows bulk insertion of cart items
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'size',
    ];

    /**
     * Relationship: Cart item belongs to a user
     * Each cart entry is owned by one customer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Cart item belongs to a product
     * Each cart entry references one product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate subtotal for this cart item
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->product->price;
    }

    /**
     * Get formatted subtotal with currency
     * @return string
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Check if product is still available in requested quantity
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->product->is_active && 
               $this->product->hasSufficientStock($this->quantity);
    }
}