<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates products table with foreign key to categories
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Link to category
            $table->string('name'); // Product name
            $table->text('description')->nullable(); // Product details
            $table->decimal('price', 10, 2); // Price (max 99,999,999.99)
            $table->integer('stock')->default(0); // Available quantity
            $table->string('image')->nullable(); // Product image path
            $table->string('pirt_number')->nullable(); // PIRT/Halal certification number
            $table->boolean('has_sizes')->default(false); // Does product have size variants (S,M,L,XL)
            $table->boolean('is_active')->default(true); // Product visibility
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};