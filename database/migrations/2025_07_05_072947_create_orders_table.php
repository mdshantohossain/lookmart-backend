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
            $table->decimal('order_total', 10);
            $table->tinyInteger('order_status')->default(0)->comment("0: Pending, 1: Processing, 2: Delivered, 3: Canceled");
            $table->tinyInteger('payment_type')->comment('0: Cash On Delivery, 1: Online');
            $table->tinyInteger('payment_status')->default(0)->comment('0: Unpaid; 1: Paid; 2: Refunded');
            $table->dateTime('paid_at')->nullable();
            $table->decimal('delivery_charge', 10)->default(0);
            $table->tinyInteger('charge_status')->nullable()->comment('0: Unpaid, 1: Paid');
            $table->text('delivery_address');
            $table->dateTime('delivery_at')->nullable();
            $table->text('phone');
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
