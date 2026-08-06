<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE `orders` MODIFY `status` ENUM('Pending','Accepted','Packed','Shipped','Out for Delivery','Delivered','Cancelled','Declined') NOT NULL DEFAULT 'Pending'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement(
            "ALTER TABLE `orders` MODIFY `status` ENUM('Pending','Accepted','Declined') NOT NULL DEFAULT 'Pending'"
        );
    }
};
