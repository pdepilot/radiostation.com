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
        Schema::create('post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable(); // For guest likes
            $table->string('user_agent')->nullable(); // For guest likes
            $table->timestamps();
            
            // Prevent duplicate likes from same user/IP
            $table->unique(['news_post_id', 'user_id'], 'unique_user_like');
            $table->unique(['news_post_id', 'ip_address'], 'unique_ip_like');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_likes');
    }
};
