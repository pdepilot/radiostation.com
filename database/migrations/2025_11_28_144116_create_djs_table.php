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
        Schema::create('djs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('stage_name')->nullable();
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('specialty')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('mixcloud')->nullable();
            $table->string('booking_link')->nullable();
            $table->json('availability')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('djs');
    }
};
