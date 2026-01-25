<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_knowledge', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->text('response');
            $table->json('question_patterns')->nullable(); // Array of regex patterns
            $table->string('category')->nullable(); // e.g., 'advertising', 'contact', 'shows'
            $table->integer('priority')->default(0); // Higher priority = checked first
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_knowledge');
    }
};

