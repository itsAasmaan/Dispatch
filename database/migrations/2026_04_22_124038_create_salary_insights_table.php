<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('role_title');
            $table->enum('role_type', ['full-time', 'internship', 'contract'])->default('full-time');
            $table->decimal('base_salary', 12, 2);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('stock', 12, 2)->default(0);
            $table->decimal('total_compensation', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('location')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->enum('outcome', ['offer_received', 'counter_offer', 'accepted', 'rejected'])->default('offer_received');
            $table->year('offer_year')->nullable();
            $table->boolean('is_anonymous')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_insights');
    }
};