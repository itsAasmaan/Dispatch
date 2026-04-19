<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'status',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'skipped_answers',
        'score_percentage',
        'points_earned',
        'points_total',
        'started_at',
        'completed_at',
        'time_taken_seconds',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function complete(): void
    {
        $correct  = $this->answers()->where('self_rating', 'correct')->count();
        $wrong    = $this->answers()->where('self_rating', 'incorrect')->count();
        $partial  = $this->answers()->where('self_rating', 'partial')->count();
        $skipped  = $this->answers()->where('self_rating', 'skipped')->count();
        $total    = $this->total_questions;

        $pointsEarned = $correct + ($partial * 0.5);
        $percentage   = $total > 0
            ? round(($pointsEarned / $total) * 100, 2)
            : 0;

        $this->update([
            'status'             => 'completed',
            'correct_answers'    => $correct,
            'wrong_answers'      => $wrong,
            'skipped_answers'    => $skipped,
            'points_earned'      => $pointsEarned,
            'points_total'       => $total,
            'score_percentage'   => $percentage,
            'completed_at'       => now(),
            'time_taken_seconds' => now()->diffInSeconds($this->started_at),
        ]);
    }
}