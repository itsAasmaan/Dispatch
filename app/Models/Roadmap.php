<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Roadmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'target_role',
        'level',
        'estimated_hours',
        'enrolled_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Roadmap $roadmap) {
            $roadmap->slug = Str::slug($roadmap->title);
        });
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'roadmap_topics')
            ->withPivot('order', 'is_required')
            ->orderByPivot('order');
    }

    public function roadmapTopics(): HasMany
    {
        return $this->hasMany(RoadmapTopic::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(RoadmapEnrollment::class);
    }

    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'roadmap_enrollments')
            ->withPivot('status', 'completion_percentage', 'enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('target_role', $role);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function isEnrolledBy(User $user): bool
    {
        return $this->enrollments()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public function enrollmentFor(User $user): ?RoadmapEnrollment
    {
        return $this->enrollments()
            ->where('user_id', $user->id)
            ->first();
    }

    public function totalTopics(): int
    {
        return $this->topics()->count();
    }

    public function requiredTopics(): int
    {
        return $this->topics()->wherePivot('is_required', true)->count();
    }
}