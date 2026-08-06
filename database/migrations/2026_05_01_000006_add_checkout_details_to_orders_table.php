<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('delivery_address')->nullable()->after('total_amount');
            $table->enum('delivery_method', ['standard', 'express'])->default('standard')->after('delivery_address');
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('delivery_method');
            $table->string('contact_number')->nullable()->after('shipping_fee');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_address', 'delivery_method', 'shipping_fee', 'contact_number']);
        });
    }
};