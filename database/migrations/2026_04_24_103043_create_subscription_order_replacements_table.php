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
        Schema::dropIfExists('subscription_order_replacements');

        Schema::create('subscription_order_replacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_order_id')->constrained('subscription_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unique(['subscription_order_id', 'product_id'], 'sor_order_product_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_order_replacements');
    }
};
