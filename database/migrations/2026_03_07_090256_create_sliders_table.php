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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('image');
            $table->morphs('sliderable'); // sliderable_id + sliderable_type

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('page')->default('home'); // home / category / campaign

            $table->integer('position');
            $table->string('slug');
            $table->tinyInteger('status')->comment('0: Inactive, 1: Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
