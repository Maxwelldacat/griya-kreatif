<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates order_items table (junction table for orders and products)
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Which order this belongs to
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Which product was ordered
            $table->integer('quantity'); // How many were ordered
            $table->decimal('price', 10, 2); // Price at time of purchase (snapshot)
            $table->enum('size', ['S', 'M', 'L', 'XL'])->nullable(); // Size if applicable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};