<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'account_name',
        'qr_code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean', // otomatis casting ke boolean
    ];
}
