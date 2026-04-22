<?php

namespace App\Notifications;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewUpvotedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Interview $interview,
        public readonly string $voterName
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'interview_upvoted',
            'message' => "{$this->voterName} upvoted your interview experience at {$this->interview->company->name}",
            'interview_id' => $this->interview->id,
            'interview_title' => $this->interview->title,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your interview experience was upvoted!')
            ->line("{$this->voterName} upvoted your experience at {$this->interview->company->name}.")
            ->action('View Experience', url("/interviews/{$this->interview->id}"));
    }
}