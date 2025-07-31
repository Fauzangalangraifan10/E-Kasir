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

    /**
     * Relationship dengan category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship dengan transaction details
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class)
                    ->with('transaction'); // Pastikan relasi transaction ikut dimuat
    }

    /**
     * Mengecek apakah stok rendah
     */
    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Mendapatkan harga yang sudah diformat
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Scope untuk produk aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk produk dengan stok rendah
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= min_stock');
    }
}
