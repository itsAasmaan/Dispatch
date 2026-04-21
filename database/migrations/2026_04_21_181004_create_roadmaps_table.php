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
        Schema::create('roadmaps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->enum('target_role', [
                'frontend',
                'backend',
                'fullstack',
                'devops',
                'data_engineer',
                'mobile',
                'other',
            ]);

            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->unsignedInteger('estimated_hours')->default(0);
            $table->unsignedInteger('enrolled_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
