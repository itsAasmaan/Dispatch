<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('candidate')->get();
        $questions = Question::where('is_approved', true)->get();

        if ($users->isEmpty() || $questions->isEmpty()) {
            $this->command->warn('No users or approved questions found. Please run UserSeeder and QuestionSeeder first.');
            return;
        }

        $quizzes = [
            [
                'title' => 'DSA Fundamentals Quiz',
                'description' => 'Test your knowledge of data structures and algorithms basics.',
                'type' => 'topic',
                'topic' => 'dsa',
                'difficulty' => 'easy',
                'total_questions' => 5,
                'time_limit_minutes' => 15,
            ],
            [
                'title' => 'System Design Basics',
                'description' => 'Quiz covering system design fundamentals and common patterns.',
                'type' => 'topic',
                'topic' => 'system_design',
                'difficulty' => 'medium',
                'total_questions' => 5,
                'time_limit_minutes' => 20,
            ],
            [
                'title' => 'Google Interview Prep',
                'description' => 'Practice questions commonly asked in Google interviews.',
                'type' => 'company',
                'company' => 'Google',
                'difficulty' => 'hard',
                'total_questions' => 10,
                'time_limit_minutes' => 30,
            ],
            [
                'title' => 'Amazon Leadership Principles',
                'description' => 'Prepare for Amazon behavioral questions based on their 14 leadership principles.',
                'type' => 'company',
                'company' => 'Amazon',
                'difficulty' => 'medium',
                'total_questions' => 8,
                'time_limit_minutes' => 25,
            ],
            [
                'title' => 'React Developer Mock Test',
                'description' => 'Test your React knowledge with this comprehensive quiz.',
                'type' => 'role',
                'role' => 'frontend',
                'difficulty' => 'medium',
                'total_questions' => 10,
                'time_limit_minutes' => 30,
            ],
            [
                'title' => 'Backend Engineer Assessment',
                'description' => 'Comprehensive quiz for backend engineering roles.',
                'type' => 'role',
                'role' => 'backend',
                'difficulty' => 'hard',
                'total_questions' => 15,
                'time_limit_minutes' => 45,
            ],
        ];

        $createdQuizzes = [];
        foreach ($quizzes as $quizData) {
            $quiz = Quiz::firstOrCreate(
                ['title' => $quizData['title']],
                array_merge($quizData, [
                    'created_by' => $users->first()->id,
                    'is_active' => true,
                ])
            );
            $createdQuizzes[] = $quiz;

            // Attach random questions to the quiz
            $quizQuestions = $questions->random(min($quizData['total_questions'], $questions->count()));
            $order = 1;
            foreach ($quizQuestions as $question) {
                QuizQuestion::firstOrCreate(
                    [
                        'quiz_id' => $quiz->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'order' => $order++,
                        'point' => rand(1, 3),
                    ]
                );
            }
        }

        // Create some quiz attempts
        foreach ($createdQuizzes as $quiz) {
            $quizQuestions = $quiz->questions;
            if ($quizQuestions->isEmpty()) continue;

            // Create 2-3 attempts per quiz
            $attemptCount = rand(2, 3);
            for ($i = 0; $i < $attemptCount; $i++) {
                $user = $users->random();
                $totalQuestions = $quizQuestions->count();
                $correctAnswers = rand(0, $totalQuestions);
                $wrongAnswers = $totalQuestions - $correctAnswers;
                $scorePercentage = ($correctAnswers / $totalQuestions) * 100;

                QuizAttempt::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'quiz_id' => $quiz->id,
                    ],
                    [
                        'status' => 'completed',
                        'total_questions' => $totalQuestions,
                        'correct_answers' => $correctAnswers,
                        'wrong_answers' => $wrongAnswers,
                        'skipped_answers' => 0,
                        'score_percentage' => $scorePercentage,
                        'points_earned' => $correctAnswers * 2,
                        'points_total' => $totalQuestions * 2,
                        'started_at' => now()->subDays(rand(1, 30)),
                        'completed_at' => now()->subDays(rand(1, 30))->addMinutes(rand(10, 45)),
                        'time_taken_seconds' => rand(600, 2700),
                    ]
                );
            }
        }

        $this->command->info('Quizzes seeded: ' . count($quizzes) . ' quizzes with questions and attempts.');
    }
}