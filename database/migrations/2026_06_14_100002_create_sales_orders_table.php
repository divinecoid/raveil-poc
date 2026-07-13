<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_number')->unique();
            $table->foreignUuid('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('Pending'); // Pending, Processing, Completed, Cancelled
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
