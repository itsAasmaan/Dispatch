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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
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
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->unsignedInteger('estimated_duration_minutes')->default(60);
            $table->json('resources')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
