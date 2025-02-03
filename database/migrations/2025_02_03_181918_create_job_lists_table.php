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
                $table->string('title');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('location')->nullable();
                $table->text('description')->nullable();
                $table->text('skills')->nullable();
                $table->string('date_posted', 50);
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
