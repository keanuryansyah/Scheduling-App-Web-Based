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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->string('job_title');
            $table->string('client_name');
            $table->string('client_phone');

            $table->foreignId('job_type')
                ->constrained('job_types');

            $table->date('job_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');

            $table->enum('payment_method', ['tf', 'cash', 'vendor', 'unpaid'])
                ->default('unpaid');

            $table->decimal('amount', 12, 2)->default(0);
            $table->string('proof')->default('no-proof.img');
            $table->text('notes')->nullable();

            $table->enum('status', ['scheduled', 'ongoing', 'done', 'canceled'])
                ->default('scheduled');

            $table->foreignId('created_by')
                ->constrained('users');

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
