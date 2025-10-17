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
        Schema::create('app_manages', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->text('logo')->nullable();
            $table->text('favicon')->nullable();
            $table->text('address')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_manages');
    }
};
