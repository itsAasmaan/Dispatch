<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('answer')->nullable();
            $table->enum('category', [
                'dsa',
                'system_design',
                'behavioural',
                'frontend',
                'backend',
                'devops',
                'database',
                'other',
            ]);
            $table->enum('difficulty', [
                'easy',
                'medium',
                'hard',
            ])->default('medium');
            $table->json('tags')->nullable();
            $table->json('companies')->nullable();
            $table->unsignedInteger('upvote_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('question_upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'question_id']);
        });

        Schema::create('question_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_upvotes');
        Schema::dropIfExists('question_bookmarks');
        Schema::dropIfExists('questions');
    }
};