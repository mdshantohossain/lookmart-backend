<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use App\Models\Brand;

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
            $table->foreignIdFor(SubCategory::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->float('selling_price');
            $table->float('original_price')->nullable();
            $table->string('buy_price')->nullable();
            $table->string('sku')->unique();
            $table->string('discount')->nullable();
            $table->integer('quantity')->nullable();
            $table->text('thumbnail');
            $table->text('short_description');
            $table->longText('long_description')->nullable();
            $table->integer('total_clicked')->default(0);
            $table->json('materials')->nullable();
            $table->text('tags')->nullable();
            $table->string('variants_title')->nullable();
            $table->string('total_sold')->nullable();
            $table->integer('total_day_to_delivery')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('product_policy_id')->nullable();
            $table->boolean('is_trending')->default(0)->comment('0: false; 1: true');
            $table->boolean('is_featured')->default(0)->comment('0: false, 1: true');
            $table->boolean('is_free_delivery')->default(0)->comment('0: false, 1: true');
            $table->tinyInteger('status')->comment('0: Published, 1: Unpublished');
            $table->tinyInteger('product_owner')->default(0)->comment('0: own, 1: cj dropshopping, 2: ali express');
            $table->text('slug')->unique()->index();
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
