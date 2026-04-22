<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $followerName,
        public readonly string $companyName
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_follower',
            'message' => "{$this->followerName} started following {$this->companyName}",
            'follower_name' => $this->followerName,
            'company_name' => $this->companyName,
        ];
    }
}