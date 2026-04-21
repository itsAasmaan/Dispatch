<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadmapTopic extends Model
{
    protected $fillable = [
        'roadmap_id',
        'topic_id',
        'order',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}