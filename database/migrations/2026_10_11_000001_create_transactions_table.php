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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out']);
            $table->string('description');
            $table->unsignedBigInteger('amount'); // Nominal rupiah tanpa desimal
            $table->string('category');
            $table->string('receipt_path')->nullable();
            $table->enum('source', ['manual', 'voice', 'camera'])->default('manual');
            $table->dateTime('transacted_at');
            $table->timestamps();

            $table->index(['user_id', 'transacted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
