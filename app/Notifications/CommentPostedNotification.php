<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentPostedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Comment $comment,
        public readonly string $commenterName
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'comment_posted',
            'message' => "{$this->commenterName} commented on your post",
            'comment_id' => $this->comment->id,
            'commentable_type' => $this->comment->commentable_type,
            'commentable_id' => $this->comment->commentable_id,
        ];
    }
}