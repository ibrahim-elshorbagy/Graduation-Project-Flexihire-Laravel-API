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
        Schema::create('job_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('location', 100)->nullable();
            $table->date('date_posted')->nullable();
            $table->text('description')->nullable();
            $table->text('skills')->nullable();
            $table->integer('min_salary')->nullable();
            $table->integer('max_salary')->nullable();
            $table->boolean('salary_negotiable')->nullable();
            $table->enum('payment_period', [
                'hourly',
                'daily',
                'weekly',
                'monthly',
                'yearly'
            ])->nullable();
            $table->string('payment_currency', 3)->default('USD');
            $table->boolean('hiring_multiple_candidates')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_lists');
    }
};
