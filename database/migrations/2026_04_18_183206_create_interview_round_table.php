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
        Schema::create('interview_round', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('round_number');
            $table->enum('round_type', [
                'hr',
                'technical',
                'system_design',
                'dsa',
                'managerial',
                'assignment',
                'cultural_fit',
                'behavioural',
                'frontend',
                'backend',
                'other',
            ]);

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('tips')->nullable();
            $table->integer('duration_minutes')->nullable();

            $table->enum('difficulty', [
                'easy',
                'medium',
                'hard',
            ])->default('medium');

            $table->boolean('cleared')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_round');
    }
};
