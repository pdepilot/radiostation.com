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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('venue')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('event_end_date')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('ticket_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('upcoming'); // upcoming, ongoing, past, cancelled
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
