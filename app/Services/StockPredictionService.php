<?php

namespace App\Services;

use OpenAI;

class StockPredictionService
{
    public function predictStockRunOut(string $productName, array $salesData): int
    {
        // Inisialisasi client OpenAI
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        // Ubah data penjualan menjadi string
        $salesHistory = implode(", ", $salesData);

        // Buat prompt untuk AI
        $prompt = "Berdasarkan data penjualan produk '{$productName}' dalam 7 hari terakhir: {$salesHistory} unit per hari.
        Prediksi berapa hari lagi stok akan habis jika tren tetap sama.
        Jawab hanya dengan angka hari tanpa tambahan kata lain.";

        // Panggil API OpenAI
        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Kamu adalah AI yang membantu prediksi stok.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        // Cek apakah response memiliki data yang valid
        $content = $response->choices[0]->message->content ?? '0';

        // Ambil hanya angka dari jawaban AI
        return (int) filter_var($content, FILTER_SANITIZE_NUMBER_INT);
    }
}
