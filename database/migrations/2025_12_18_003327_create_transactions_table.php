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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained('jobs')->onDelete('set null');
            $table->decimal('amount', 15, 2); // Nominal uang
            $table->enum('type', ['income', 'payout']); // Income (gaji masuk), Payout (reset mingguan)
            $table->text('description')->nullable(); // Ket: "Gaji Job A", "Reset Minggu 1"
            $table->date('transaction_date'); // PENTING: Untuk filter per hari/bulan
            $table->timestamps();
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
