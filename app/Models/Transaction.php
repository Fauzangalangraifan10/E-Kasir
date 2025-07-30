<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon; // Tambahkan ini jika Anda ingin menggunakan Carbon untuk casts

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_code',
        'total_price',
        'paid',
        'change',
        'payment_method',
    ];
    
    // Asumsi ada kolom 'created_at' yang otomatis mencatat waktu transaksi.
    // Jika Anda memiliki kolom 'transaction_date' terpisah, tambahkan ke $casts.
    protected $casts = [
        'created_at' => 'datetime', // Mengubah 'created_at' menjadi objek Carbon secara otomatis
        // 'transaction_date' => 'datetime', // Jika Anda punya kolom transaction_date
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke detail-detail transaksi
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}