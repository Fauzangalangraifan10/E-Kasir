<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'barcode',
        'description',
        'image',
        'price',
        'stock',
        'min_stock',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationship dengan category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship dengan transaction details
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Check if stock is low
    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->min_stock;
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for low stock products
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= min_stock');
    }
}