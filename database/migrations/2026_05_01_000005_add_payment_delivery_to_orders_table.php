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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'gcash'])->default('cash')->after('total_amount');
            $table->foreignId('gcash_transaction_id')->nullable()->constrained('gcash_transactions')->onDelete('set null')->after('payment_method');
            $table->enum('payment_status', ['unpaid', 'pending', 'completed', 'failed', 'refunded'])->default('unpaid')->after('gcash_transaction_id');
            $table->enum('delivery_status', ['Pending', 'Shipped', 'Out for Delivery', 'Delivered', 'Cancelled'])->default('Pending')->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['gcash_transaction_id']);
            $table->dropColumn(['payment_method', 'gcash_transaction_id', 'payment_status', 'delivery_status', 'paid_at']);
        });
    }
};
