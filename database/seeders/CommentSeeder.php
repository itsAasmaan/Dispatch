<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Interview;
use App\Models\Question;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('candidate')->get();
        $interviews = Interview::all();
        $questions = Question::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $comments = [];

        // Comments on interviews
        foreach ($interviews as $interview) {
            $commentCount = rand(1, 3);
            for ($i = 0; $i < $commentCount; $i++) {
                $comments[] = [
                    'user' => $users->random(),
                    'commentable_type' => Interview::class,
                    'commentable_id' => $interview->id,
                    'body' => $this->getRandomInterviewComment(),
                ];
            }
        }

        // Comments on questions
        foreach ($questions as $question) {
            $commentCount = rand(1, 2);
            for ($i = 0; $i < $commentCount; $i++) {
                $comments[] = [
                    'user' => $users->random(),
                    'commentable_type' => Question::class,
                    'commentable_id' => $question->id,
                    'body' => $this->getRandomQuestionComment(),
                ];
            }
        }

        // Create comments
        $createdComments = [];
        foreach ($comments as $commentData) {
            $user = $commentData['user'];
            unset($commentData['user']);

            $comment = Comment::create(array_merge($commentData, [
                'user_id' => $user->id,
                'upvote_count' => rand(0, 20),
            ]));
            $createdComments[] = $comment;
        }

        // Add some replies to comments
        foreach ($createdComments as $comment) {
            if (rand(0, 1)) { // 50% chance of having a reply
                $reply = Comment::create([
                    'user_id' => $users->random()->id,
                    'commentable_type' => $comment->commentable_type,
                    'commentable_id' => $comment->commentable_id,
                    'parent_id' => $comment->id,
                    'body' => $this->getRandomReplyComment(),
                    'upvote_count' => rand(0, 10),
                ]);
            }
        }

        $this->command->info('Comments seeded: ' . count($comments) . ' comments with replies.');
    }

    private function getRandomInterviewComment()
    {
        $comments = [
            'Thanks for sharing this experience! How many LeetCode medium questions did they ask?',
            'Great breakdown! What was the difficulty level of the system design round?',
            'Did they ask about your past projects in detail?',
            'Thanks for the detailed overview. How long did the whole process take?',
            'This is really helpful! Did you prepare in any specific way?',
            'Congratulations on the offer! What resources helped you the most?',
            'How was the cultural fit round? Were the questions predictable?',
            'Did they ask behavioral questions? If so, which ones?',
            'Thanks for sharing! How was the interview atmosphere?',
            'This is exactly what I needed. Good luck with your decision!',
        ];
        return $comments[array_rand($comments)];
    }

    private function getRandomQuestionComment()
    {
        $comments = [
            'Great explanation! Could you provide more details on the time complexity?',
            'This is really helpful. Have you considered the space complexity as well?',
            'Thanks for sharing! I have a slightly different approach...',
            'Can you explain this with an example?',
            'This makes so much sense now. Thank you!',
            'I had a similar problem in my interview. Your answer is clearer.',
            'Would this approach work for edge cases?',
            'Nice solution! Here\'s an alternative approach...',
            'This is exactly what I was looking for.',
            'Could you elaborate on the space optimization?',
        ];
        return $comments[array_rand($comments)];
    }

    private function getRandomReplyComment()
    {
        $replies = [
            'Great point! I didn\'t consider that.',
            'Thanks for the补充!',
            'That\'s a valid approach. Thanks for sharing!',
            'I see what you mean. I\'ll update my answer.',
            'Good suggestion! I\'ll add more details.',
            'Absolutely! That\'s an important consideration.',
            'Thanks for the feedback!',
            'You\'re right, I should have mentioned that.',
            'Great observation!',
            'I appreciate the input!',
        ];
        return $replies[array_rand($replies)];
    }
}