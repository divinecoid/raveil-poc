<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignUuid('sales_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('todo'); // todo, in_progress, review, done
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
