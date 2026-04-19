<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', [
                'topic',
                'company',
                'role',
                'mixed',
                'mock_interview',
            ]);
            $table->string('topic')->nullable();
            $table->string('company')->nullable();
            $table->string('role')->nullable();
            $table->enum('difficulty', [
                'easy',
                'medium',
                'hard',
                'mixed',
            ])->default('mixed');
            $table->unsignedInteger('total_questions')->default(10);
            $table->unsignedInteger('time_limit_minutes')->default(30);
            $table->boolean('is_timed')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
