<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreparationTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'preparation_plan_id',
        'title',
        'description',
        'type',
        'topic_id',
        'quiz_id',
        'due_date',
        'day_number',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'due_date'     => 'date',
        'completed_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PreparationPlan::class, 'preparation_plan_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        // Update plan progress and streak
        $this->plan->recalculateProgress();
        $this->plan->updateStreak();
    }

    public function markSkipped(): void
    {
        $this->update(['status' => 'skipped']);
        $this->plan->recalculateProgress();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}