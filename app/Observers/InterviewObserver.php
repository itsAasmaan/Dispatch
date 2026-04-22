<?php

namespace App\Observers;

use App\Models\Interview;
use App\Notifications\InterviewUpvotedNotification;

class InterviewObserver
{
    public function updated(Interview $interview): void
    {
        if (
            $interview->wasChanged('upvote_count') &&
            $interview->upvote_count > $interview->getOriginal('upvote_count')
        ) {

            $voter = auth()->user();

            if ($voter && $voter->id !== $interview->user_id) {
                $interview->user->notify(
                    new InterviewUpvotedNotification($interview, $voter->name)
                );
            }
        }
    }
}