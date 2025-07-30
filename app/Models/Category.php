<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // Relationship dengan products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Count products in category
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }
}