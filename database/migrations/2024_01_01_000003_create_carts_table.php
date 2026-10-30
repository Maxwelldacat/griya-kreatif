<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates carts table for shopping cart functionality
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Customer who owns this cart
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Product in cart
            $table->integer('quantity')->default(1); // Number of items
            $table->enum('size', ['S', 'M', 'L', 'XL'])->nullable(); // Size selection for clothing
            $table->timestamps();
            
            // Prevent duplicate cart entries for same product+size combination
            $table->unique(['user_id', 'product_id', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};