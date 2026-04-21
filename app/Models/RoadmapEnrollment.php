<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadmapEnrollment extends Model
{
    protected $fillable = [
        'user_id',
        'roadmap_id',
        'status',
        'completion_percentage',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function recalculateProgress(): void
    {
        $roadmap = $this->roadmap;
        $totalTopics = $roadmap->totalTopics();

        if ($totalTopics === 0) {
            return;
        }

        $completedTopics = CandidateTopicProgress::where('user_id', $this->user_id)
            ->where('roadmap_id', $this->roadmap_id)
            ->where('status', 'completed')
            ->count();

        $percentage = round(($completedTopics / $totalTopics) * 100);

        $this->update([
            'completion_percentage' => $percentage,
            'status' => $percentage >= 100 ? 'completed' : 'active',
            'completed_at' => $percentage >= 100 ? now() : null,
        ]);
    }
}