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
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('dj_id')->nullable()->constrained('djs')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'live', 'offline'])->default('offline');
            $table->string('stream_url')->nullable();
            $table->boolean('chat_enabled')->default(true);
            $table->unsignedInteger('listener_count')->default(0);
            $table->string('server_host')->nullable();
            $table->unsignedInteger('bitrate')->nullable();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_streams');
    }
};
