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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('role_title');
            $table->string('role_type')->nullable();

            $table->date('interview_date')->nullable();
            $table->string('location')->nullable();
            $table->integer('total_rounds')->default(1);
            $table->integer('years_of_experience')->default(0);

            $table->enum('outcome', [
                'offer_received',
                'rejected',
                'ghosted',
                'pending',
                'withdrew',
            ])->default('pending');

            $table->unsignedTinyInteger('difficulty')->default(3);
            $table->unsignedTinyInteger('overall_rating')->default(3);

            // Content
            $table->string('title');
            $table->text('description');
            $table->json('tags')->nullable();

            $table->unsignedInteger('upvote_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('comment_count')->default(0);
            $table->unsignedInteger('bookmark_count')->default(0);

            // Status
            $table->enum('status', [
                'draft',
                'published',
                'archived',
            ])->default('draft');

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('interview_upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'interview_id']);
        });

        Schema::create('interview_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'interview_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
        Schema::dropIfExists('interview_upvotes');
        Schema::dropIfExists('interview_bookmarks');
    }
};
