<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates orders table for purchase records
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Customer who placed order
            $table->string('order_number')->unique(); // Unique order identifier (e.g., ORD-20240101-0001)
            $table->decimal('total_amount', 10, 2); // Total price of order
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Order status
            $table->text('customer_notes')->nullable(); // Special instructions from customer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};