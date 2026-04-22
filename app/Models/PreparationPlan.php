<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreparationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'target_role',
        'interview_date',
        'start_date',
        'status',
        'total_tasks',
        'completed_tasks',
        'completion_percentage',
        'current_streak',
        'longest_streak',
        'last_activity_date',
    ];

    protected $casts = [
        'interview_date' => 'date',
        'start_date' => 'date',
        'last_activity_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(PreparationTask::class)->orderBy('day_number');
    }

    public function todayTasks(): HasMany
    {
        return $this->hasMany(PreparationTask::class)
            ->whereDate('due_date', today());
    }

    public function recalculateProgress(): void
    {
        $total = $this->tasks()->count();
        $completed = $this->tasks()->where('status', 'completed')->count();
        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        $this->update([
            'total_tasks' => $total,
            'completed_tasks' => $completed,
            'completion_percentage' => $percentage,
            'status' => $percentage >= 100 ? 'completed' : 'active',
        ]);
    }

    public function updateStreak(): void
    {
        $today = today();
        $lastActivity = $this->last_activity_date;

        if (!$lastActivity) {
            $this->update([
                'current_streak' => 1,
                'longest_streak' => 1,
                'last_activity_date' => $today,
            ]);
            return;
        }

        $daysDiff = $lastActivity->diffInDays($today);

        if ($daysDiff === 0) {
            // Already updated today
            return;
        }

        if ($daysDiff === 1) {
            // Consecutive day — increment streak
            $newStreak = $this->current_streak + 1;
            $this->update([
                'current_streak' => $newStreak,
                'longest_streak' => max($newStreak, $this->longest_streak),
                'last_activity_date' => $today,
            ]);
        } else {
            // Streak broken
            $this->update([
                'current_streak' => 1,
                'last_activity_date' => $today,
            ]);
        }
    }

    public function daysUntilInterview(): ?int
    {
        return $this->interview_date
            ? today()->diffInDays($this->interview_date, false)
            : null;
    }
}