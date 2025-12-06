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
            $table->string('vid')->nullable();
            $table->string('sku')->nullable();
            $table->string('color')->nullable();
            $table->string('variant_key')->nullable();
            // Images
            $table->text('image')->nullable(); // optional extra images
            // Pricing
            $table->decimal('buy_price', 10)->nullable(); // CJ buy price
            $table->string('suggested_price', 10)->nullable(); // Suggested price
            $table->decimal('selling_price', 10)->nullable(); // your custom selling price
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
