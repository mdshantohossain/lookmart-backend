<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->float('order_total');
            $table->unsignedBigInteger('order_timestamp');
            $table->tinyInteger('order_status')->default(0)->comment("0: Pending, 1: Processing, 2: Delivered, 3: Canceled");
            $table->text('delivery_charge')->nullable();
            $table->text('delivery_address');
            $table->text('delivery_date')->nullable();
            $table->unsignedBigInteger('delivery_timestamp')->nullable();
            $table->string('delivery_within')->nullable();
            $table->tinyInteger('payment_method')->comment('0: Cash On Delivery, 1: Online');
            $table->tinyInteger('payment_status')->default(0)->nullable()->comment('0: Pending, 1: Success, 2: Canceled, 3: Failed');
            $table->unsignedBigInteger('payment_timestamp')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('card_type')->nullable();
            $table->text('transaction_id')->nullable()->unique();
            $table->string('currency')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
