<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Rokok & Tembakau', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Makanan Ringan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebutuhan Sehari-hari', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Obat-obatan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alat Tulis', 'created_at' => now(), 'updated_at' => now()],
        ];

        Category::insert($categories);
    }
}