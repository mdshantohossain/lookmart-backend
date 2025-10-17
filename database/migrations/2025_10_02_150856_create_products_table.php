<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Models\ProductPolicy;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('cj_id')->nullable();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SubCategory::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->float('selling_price');
            $table->float('regular_price')->nullable();
            $table->string('buy_price')->nullable();
            $table->string('sku')->unique();
            $table->string('discount')->nullable();
            $table->integer('quantity');
            $table->string('main_image');
            $table->text('video')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->integer('total_clicked')->default(0);
            $table->json('materials')->nullable();
            $table->json('tags')->nullable();
            $table->string('sizes')->nullable();
            $table->json('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('product_policies')->nullable();
            $table->tinyInteger('is_trending')->default(0)->comment('0: false; 1: true');
            $table->tinyInteger('is_featured')->nullable()->comment('0: false, 1: true');
            $table->tinyInteger('status');
            $table->text('slug')->unique();
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
