<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number')->unique();
            $table->foreignUuid('sales_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status')->default('Unpaid'); // Unpaid, Paid, Cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
