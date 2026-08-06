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
        Schema::table('products', function (Blueprint $table) {
            // Drop old columns from the real estate model
            $table->dropColumn(['city', 'country', 'year_listed', 'number_of_rooms']);
            
            // Add new columns for Pasalubong Center inventory
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
            $table->string('sku_code')->unique()->after('category_id');
            $table->string('name')->after('sku_code');
            $table->text('description')->nullable()->after('name');
            $table->decimal('price', 10, 2)->after('description');
            $table->integer('stock_quantity')->default(0)->after('price');
            $table->integer('low_stock_threshold')->default(10)->after('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'sku_code', 'name', 'description', 'price', 'stock_quantity', 'low_stock_threshold']);
            
            // Restore legacy columns
            $table->string('city');
            $table->string('country');
            $table->year('year_listed');
            $table->integer('number_of_rooms')->nullable();
        });
    }
};
