<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Defines fillable fields for order creation
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'shipping_method', 
        'payment_method', 
        'shipping_cost', 
        'customer_notes',
    ];

    /**
     * The attributes that should be cast.
     * Converts total_amount to decimal with 2 decimal places
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relationship: Order belongs to a user (customer)
     * Each order is placed by one customer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Order has many order items
     * Each order contains multiple products
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate unique order number
     * Format: ORD-YYYYMMDD-XXXX
     * @return string
     */
    public static function generateOrderNumber(): string
    {
        $date = date('Ymd');
        $lastOrder = self::whereDate('created_at', today())
                         ->orderBy('id', 'desc')
                         ->first();
        
        $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
        
        return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted total amount with currency
     * @return string
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Menentukan status tampilan yang lebih spesifik (UI)
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'completed') return 'Selesai';
        if ($this->status === 'cancelled') return 'Dibatalkan';

        if ($this->status === 'pending') {
            // Jika bukan Pick-up dan ongkir belum diinput
            if ($this->shipping_method !== 'Pick-up' && $this->shipping_cost === null) {
                return 'Menunggu Ongkir';
            }
            // Jika ongkir sudah ada (atau Pick-up), berarti tinggal bayar
            return 'Menunggu Pembayaran';
        }

        return ucfirst($this->status);
    }

    /**
     * Warna badge untuk status tampilan
     */
    public function getDisplayStatusBadgeAttribute(): string
    {
        return match($this->display_status) {
            'Menunggu Ongkir' => 'danger',     // Merah (Butuh aksi Admin)
            'Menunggu Pembayaran' => 'warning', // Kuning (Butuh aksi Pembeli)
            'Selesai' => 'success',            // Hijau
            'Dibatalkan' => 'secondary',       // Abu-abu
            default => 'secondary',
        };
    }
    
    /**
     * Get order status badge color for UI
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Scope: Get completed orders only
     * Usage: Order::completed()->get()
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Get orders for a specific date range
     * Usage: Order::dateRange($start, $end)->get()
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}