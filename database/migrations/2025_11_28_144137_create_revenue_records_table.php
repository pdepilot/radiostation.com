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
        Schema::create('revenue_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertising_package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sponsor_name');
            $table->string('contact_email')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('NGN');
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->string('invoice_number')->unique();
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('notes', 800)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_records');
    }
};
