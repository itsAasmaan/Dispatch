<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'avatar',
    'bio',
    'current_role',
    'current_company',
    'years_of_experience',
    'github_url',
    'linkedin_url',
    'portfolio_url',
    'oauth_provider',
    'oauth_provider_id',
    'is_active',
    'last_login_at',
])]

#[Hidden(['password', 'remember_token', 'oauth_provider_id'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function followedCompanies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_followers')->withTimestamps();
    }

    public function addedCompanies(): HasMany
    {
        return $this->hasMany(Company::class, 'added_by');
    }

    public function roadmapEnrollments(): HasMany
    {
        return $this->hasMany(RoadmapEnrollment::class);
    }

    public function topicProgress(): HasMany
    {
        return $this->hasMany(CandidateTopicProgress::class);
    }

    public function enrolledRoadmaps(): BelongsToMany
    {
        return $this->belongsToMany(Roadmap::class, 'roadmap_enrollments')
            ->withPivot('status', 'completion_percentage', 'enrolled_at')
            ->withTimestamps();
    }

    public function bookmarkedInterviews(): BelongsToMany
    {
        return $this->belongsToMany(Interview::class, 'interview_bookmarks')->withTimestamps();
    }
}
