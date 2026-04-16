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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('tagline')->nullable();

            // Company details
            $table->string('industry')->nullable();
            $table->string('headquarters')->nullable();
            $table->enum('size', [
                '1-10',
                '11-50',
                '51-200',
                '201-500',
                '501-1000',
                '1001-5000',
                '5001+',
            ])->nullable();
            $table->year('founded_year')->nullable();

            // Social links
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('glassdoor_url')->nullable();

            // Stats (denormalized for performance)
            $table->unsignedInteger('interview_count')->default(0);
            $table->unsignedInteger('follower_count')->default(0);
            $table->decimal('average_difficulty', 3, 2)->default(0.00);  // 1.00 - 5.00
            $table->decimal('average_rating', 3, 2)->default(0.00);      // 1.00 - 5.00

            // Status
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);

            // Who added this company
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Company followers pivot table
        Schema::create('company_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_followers');
        Schema::dropIfExists('companies');
    }
};
