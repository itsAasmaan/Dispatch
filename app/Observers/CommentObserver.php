<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Interview;
use App\Notifications\CommentPostedNotification;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        $commenter = auth()->user();
        $commentable = $comment->commentable;

        if (!$commentable)
            return;
        $owner = match (true) {
            $commentable instanceof Interview => $commentable->user,
            default => null,
        };

        if ($owner && $owner->id !== $commenter?->id) {
            $owner->notify(
                new CommentPostedNotification($comment, $commenter?->name ?? 'Someone')
            );
        }
    }
}