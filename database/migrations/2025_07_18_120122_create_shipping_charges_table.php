<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_charges', function (Blueprint $table) {
            $table->id();
            $table->string('city_name');
            $table->string('charge');
            $table->tinyInteger('is_free')->default(0)->comment('0 - no, 1 - yes');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 - inactive, 1 - active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_charges');
    }
};
