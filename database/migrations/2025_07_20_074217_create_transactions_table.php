<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->string('transaction_code')->unique(); // Kode transaksi unik
            $table->decimal('total_price', 10, 2);            // Total harga semua item
            $table->decimal('paid', 10, 2);               // Jumlah yang dibayar
            $table->decimal('change', 10, 2);             // Kembalian
            $table->timestamps();                         // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
