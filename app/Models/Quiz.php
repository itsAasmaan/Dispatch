<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'topic',
        'company',
        'role',
        'difficulty',
        'total_questions',
        'time_limit_minutes',
        'is_timed',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_timed' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')->withPivot('order', 'point')->orderByPivot('order');
    }

    public function quizQuestions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function attemptedBy(User $user): bool
    {
        return $this->attempts()->where('user_id', $user->id)->exists();
    }

    public function latestAttemptBy(User $user): ?QuizAttempt
    {
        return $this->attempts()->where('user_id', $user->id)->latest()->first();
    }
}
