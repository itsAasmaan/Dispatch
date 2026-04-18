<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'role_title',
        'role_type',
        'interview_date',
        'location',
        'total_rounds',
        'years_of_experience',
        'outcome',
        'difficulty',
        'overall_rating',
        'title',
        'description',
        'tags',
        'status',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'interview_date' => 'date',
        'published_at' => 'datetime',
        'difficulty' => 'integer',
        'overall_rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Interview::class)->orderBy('round_number');
    }

    public function upvotedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'interview_upvotes')->withTimestamps();
    }

    public function bookmarkedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'interview_bookmarks')->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByOutcome($query, string $outcome)
    {
        return $query->where('outcome', $outcome);
    }

    public function scopeByDifficulty($query, int $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function isUpvotedBy(User $user): bool
    {
        return $this->upvotedBy()->where('user_id', $user->id)->exists();
    }

    public function isBookmarkedBy(User $user): bool
    {
        return $this->bookmarkedBy()->where('user_id', $user->id)->exists();
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }
}
