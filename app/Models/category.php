<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Allows these fields to be filled via create() or update()
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Relationship: Category has many products
     * Each category can contain multiple products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get active products count for this category
     * Useful for displaying category statistics
     */
    public function activeProductsCount()
    {
        return $this->products()->where('is_active', true)->count();
    }

    /**
     * Scope: Get categories that have active products
     * Usage: Category::withActiveProducts()->get()
     */
    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('products', function($q) {
            $q->where('is_active', true);
        });
    }
}