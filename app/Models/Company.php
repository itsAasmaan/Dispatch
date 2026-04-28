<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'website',
        'logo',
        'description',
        'tagline',
        'industry',
        'headquarters',
        'size',
        'founded_year',
        'linkedin_url',
        'twitter_url',
        'glassdoor_url',
        'interview_count',
        'follower_count',
        'average_difficulty',
        'average_rating',
        'is_verified',
        'is_active',
        'added_by',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'average_difficulty' => 'float',
        'average_rating' => 'float',
        'interview_count' => 'integer',
        'follower_count' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Company $company) {
            $company->slug = static::generateUniqueSlug($company->name);
        });
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_followers')->withTimestamps();
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('user_id', $user->id)->exists();
    }

    public function incrementInterviewCount(): void
    {
        $this->increment('interview_count');
    }

    public function incrementFollowerCount(): void
    {
        $this->increment('follower_count');
    }

    public function decrementFollowerCount(): void
    {
        $this->decrement('follower_count');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByIndustry($query, string $industry)
    {
        return $query->where('industry', $industry);
    }
    
    private static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
