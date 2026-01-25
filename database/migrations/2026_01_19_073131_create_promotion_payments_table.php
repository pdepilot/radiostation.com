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
        Schema::create('promotion_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('music_promotion_id')->constrained('music_promotions')->cascadeOnDelete();
            $table->string('paystack_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('paystack_response')->nullable();
            $table->timestamps();
            
            $table->index('paystack_reference');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_payments');
    }
};
