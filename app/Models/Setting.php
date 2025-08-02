<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings'; // Pastikan nama tabel sama dengan database

    protected $fillable = [
        'store_name',
        'logo',
        'address',
        'phone',
        'tax',
        'discount'
    ];
}
