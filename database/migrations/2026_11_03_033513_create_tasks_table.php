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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->dateTime('deadline');
            $table->string('difficulty', 10);
            $table->string('status', 15)->default('todo');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'deadline']);
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'difficulty']);
            $table->index(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
