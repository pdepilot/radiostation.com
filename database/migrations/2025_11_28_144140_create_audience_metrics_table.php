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
        Schema::create('audience_metrics', function (Blueprint $table) {
            $table->id();
            $table->date('captured_for')->unique();
            $table->unsignedInteger('peak_listeners')->default(0);
            $table->unsignedInteger('average_listeners')->default(0);
            $table->unsignedInteger('new_followers')->default(0);
            $table->unsignedInteger('chat_messages')->default(0);
            $table->unsignedInteger('podcast_streams')->default(0);
            $table->unsignedInteger('sms_votes')->default(0);
            $table->json('top_cities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audience_metrics');
    }
};
