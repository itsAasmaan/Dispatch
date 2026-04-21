<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'difficulty',
        'estimated_duration_minutes',
        'resources',
        'is_active',
    ];

    protected $casts = [
        'resources' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Topic $topic) {
            $topic->slug = Str::slug($topic->title);
        });
    }

    public function roadmaps(): BelongsToMany
    {
        return $this->belongsToMany(Roadmap::class, 'roadmap_topics')
            ->withPivot('order', 'is_required')
            ->orderByPivot('order');
    }

    public function candidateProgress(): HasMany
    {
        return $this->hasMany(CandidateTopicProgress::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function progressFor(User $user, int $roadmapId): ?CandidateTopicProgress
    {
        return $this->candidateProgress()
            ->where('user_id', $user->id)
            ->where('roadmap_id', $roadmapId)
            ->first();
    }
}