<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('vid')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            // Images
            $table->string('images')->nullable(); // optional extra images

            // Pricing
            $table->decimal('buy_price', 10)->nullable(); // CJ buy price
            $table->string('suggest_price', 10)->nullable(); // Suggested price
            $table->decimal('selling_price', 10)->nullable(); // your custom selling price

            $table->string('weight')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('length')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
