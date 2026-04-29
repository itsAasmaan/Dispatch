<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\InterviewRound;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class InterviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('candidate')->get();
        $companies = Company::all();

        if ($users->isEmpty() || $companies->isEmpty()) {
            $this->command->warn('No users or companies found. Please run UserSeeder and CompanySeeder first.');
            return;
        }

        $interviews = [
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Google')->first(),
                'role_title' => 'Software Engineer',
                'role_type' => 'full-time',
                'title' => 'Google L4 Interview Experience',
                'description' => 'Full process for Google L4 position. Multiple rounds covering DSA, system design, and behavioral questions.',
                'interview_date' => '2026-03-15',
                'location' => 'Mountain View, CA',
                'total_rounds' => 5,
                'years_of_experience' => 3,
                'outcome' => 'offer_received',
                'difficulty' => 4,
                'overall_rating' => 5,
                'status' => 'published',
                'tags' => ['google', 'software-engineer', 'l4'],
                'rounds' => [
                    ['round_type' => 'dsa', 'round_number' => 1, 'title' => 'Coding Round 1', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'dsa', 'round_number' => 2, 'title' => 'Coding Round 2', 'difficulty' => 'hard', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'system_design', 'round_number' => 3, 'title' => 'System Design', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'behavioural', 'round_number' => 4, 'title' => 'Leadership Principles', 'difficulty' => 'easy', 'duration_minutes' => 30, 'cleared' => true],
                    ['round_type' => 'hr', 'round_number' => 5, 'title' => 'HR Round', 'difficulty' => 'easy', 'duration_minutes' => 30, 'cleared' => true],
                ],
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Amazon')->first(),
                'role_title' => 'SDE II',
                'role_type' => 'full-time',
                'title' => 'Amazon SDE II Interview',
                'description' => 'Interview process for Amazon SDE II role. Focus on leadership principles and coding skills.',
                'interview_date' => '2026-03-20',
                'location' => 'Seattle, WA',
                'total_rounds' => 4,
                'years_of_experience' => 4,
                'outcome' => 'offer_received',
                'difficulty' => 3,
                'overall_rating' => 4,
                'status' => 'published',
                'tags' => ['amazon', 'sde ii', 'leadership'],
                'rounds' => [
                    ['round_type' => 'dsa', 'round_number' => 1, 'title' => 'Online Assessment', 'difficulty' => 'medium', 'duration_minutes' => 90, 'cleared' => true],
                    ['round_type' => 'technical', 'round_number' => 2, 'title' => 'Phone Screen', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'system_design', 'round_number' => 3, 'title' => 'System Design', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'behavioural', 'round_number' => 4, 'title' => 'Bar Raiser', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                ],
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Meta')->first(),
                'role_title' => 'Frontend Engineer',
                'role_type' => 'full-time',
                'title' => 'Meta Frontend Interview',
                'description' => 'Frontend engineering interview at Meta. Focused on React, JavaScript, and coding rounds.',
                'interview_date' => '2026-04-01',
                'location' => 'Menlo Park, CA',
                'total_rounds' => 4,
                'years_of_experience' => 2,
                'outcome' => 'rejected',
                'difficulty' => 4,
                'overall_rating' => 3,
                'status' => 'published',
                'tags' => ['meta', 'frontend', 'react'],
                'rounds' => [
                    ['round_type' => 'technical', 'round_number' => 1, 'title' => 'Phone Screen', 'difficulty' => 'medium', 'duration_minutes' => 30, 'cleared' => true],
                    ['round_type' => 'frontend', 'round_number' => 2, 'title' => 'Coding Round', 'difficulty' => 'hard', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'system_design', 'round_number' => 3, 'title' => 'Frontend System Design', 'difficulty' => 'hard', 'duration_minutes' => 45, 'cleared' => false],
                    ['round_type' => 'behavioural', 'round_number' => 4, 'title' => 'Manager Round', 'difficulty' => 'medium', 'duration_minutes' => 30, 'cleared' => true],
                ],
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Microsoft')->first(),
                'role_title' => 'Software Engineer',
                'role_type' => 'full-time',
                'title' => 'Microsoft SDE Interview',
                'description' => 'Standard Microsoft interview process for SDE position.',
                'interview_date' => '2026-04-10',
                'location' => 'Redmond, WA',
                'total_rounds' => 3,
                'years_of_experience' => 2,
                'outcome' => 'pending',
                'difficulty' => 3,
                'overall_rating' => 4,
                'status' => 'published',
                'tags' => ['microsoft', 'sde', 'standard'],
                'rounds' => [
                    ['round_type' => 'technical', 'round_number' => 1, 'title' => 'Technical Screen', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'dsa', 'round_number' => 2, 'title' => 'Coding Round', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'system_design', 'round_number' => 3, 'title' => 'System Design', 'difficulty' => 'easy', 'duration_minutes' => 30, 'cleared' => true],
                ],
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Apple')->first(),
                'role_title' => 'iOS Engineer',
                'role_type' => 'full-time',
                'title' => 'Apple iOS Developer Interview',
                'description' => 'iOS developer position at Apple. Strong focus on Swift and system design.',
                'interview_date' => '2026-04-05',
                'location' => 'Cupertino, CA',
                'total_rounds' => 5,
                'years_of_experience' => 4,
                'outcome' => 'ghosted',
                'difficulty' => 5,
                'overall_rating' => 2,
                'status' => 'published',
                'tags' => ['apple', 'ios', 'swift'],
                'rounds' => [
                    ['round_type' => 'technical', 'round_number' => 1, 'title' => 'Phone Screen', 'difficulty' => 'medium', 'duration_minutes' => 30, 'cleared' => true],
                    ['round_type' => 'dsa', 'round_number' => 2, 'title' => 'Coding Round', 'difficulty' => 'hard', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'system_design', 'round_number' => 3, 'title' => 'System Design', 'difficulty' => 'hard', 'duration_minutes' => 45, 'cleared' => true],
                    ['round_type' => 'assignment', 'round_number' => 4, 'title' => 'Take-home Assignment', 'difficulty' => 'hard', 'duration_minutes' => 180, 'cleared' => true],
                    ['round_type' => 'managerial', 'round_number' => 5, 'title' => 'Manager Interview', 'difficulty' => 'medium', 'duration_minutes' => 45, 'cleared' => true],
                ],
            ],
        ];

        foreach ($interviews as $interviewData) {
            $user = $interviewData['user'];
            $company = $interviewData['company'];
            $rounds = $interviewData['rounds'];
            unset($interviewData['user'], $interviewData['company'], $interviewData['rounds']);

            $interview = Interview::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'title' => $interviewData['title'],
                ],
                array_merge($interviewData, [
                    'upvote_count' => rand(5, 50),
                    'view_count' => rand(100, 1000),
                    'comment_count' => rand(0, 20),
                    'bookmark_count' => rand(0, 15),
                ])
            );

            foreach ($rounds as $roundData) {
                InterviewRound::firstOrCreate(
                    [
                        'interview_id' => $interview->id,
                        'round_number' => $roundData['round_number'],
                    ],
                    $roundData
                );
            }
        }

        $this->command->info('Interviews seeded: ' . count($interviews) . ' interviews with rounds.');
    }
}