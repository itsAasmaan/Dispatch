<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'round_number',
        'round_type',
        'title',
        'description',
        'tips',
        'duration_minutes',
        'difficulty',
        'cleared',
    ];

    protected $casts = [
        'cleared' => 'boolean',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }
}
