<?php
// database/migrations/xxxx_xx_xx_add_category_and_details_to_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
            $table->string('barcode')->nullable()->unique()->after('name');
            $table->text('description')->nullable()->after('barcode');
            $table->string('image')->nullable()->after('description');
            $table->integer('min_stock')->default(5)->after('stock');
            $table->boolean('is_active')->default(true)->after('min_stock');
            
            // Foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'category_id',
                'barcode', 
                'description', 
                'image', 
                'min_stock',
                'is_active'
            ]);
        });
    }
};