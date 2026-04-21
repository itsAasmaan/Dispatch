<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateTopicProgress extends Model
{
    protected $fillable = [
        'user_id',
        'topic_id',
        'roadmap_id',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function markInProgress(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => $this->started_at ?? now(),
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'completed_at' => now(),
        ]);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}