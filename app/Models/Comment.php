<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'body',
        'is_flagged',
    ];

    protected $casts = [
        'is_flagged' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user:id,name,username,avatar')->withCount('replies');
    }

    public function upvotedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_upvotes')->withTimestamps();
    }

    public function isUpvotedBy(User $user): bool
    {
        return $this->upvotedBy()->where('user_id', $user->id)->exists();
    }

    public function isRootComment(): bool
    {
        return is_null($this->parent_id);
    }
}