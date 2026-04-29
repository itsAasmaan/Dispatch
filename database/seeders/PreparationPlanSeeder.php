<?php

namespace Database\Seeders;

use App\Models\PreparationPlan;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class PreparationPlanSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('candidate')->get();
        $companies = Company::all();

        if ($users->isEmpty() || $companies->isEmpty()) {
            $this->command->warn('No users or companies found. Please run UserSeeder and CompanySeeder first.');
            return;
        }

        $plans = [
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Google')->first(),
                'title' => 'Google Interview Preparation',
                'target_role' => 'Software Engineer',
                'interview_date' => '2026-05-15',
                'start_date' => '2026-04-01',
                'status' => 'active',
                'total_tasks' => 50,
                'completed_tasks' => 20,
                'completion_percentage' => 40,
                'current_streak' => 5,
                'longest_streak' => 7,
                'last_activity_date' => '2026-04-28',
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Amazon')->first(),
                'title' => 'Amazon SDE II Prep',
                'target_role' => 'SDE II',
                'interview_date' => '2026-05-20',
                'start_date' => '2026-04-10',
                'status' => 'active',
                'total_tasks' => 60,
                'completed_tasks' => 35,
                'completion_percentage' => 58,
                'current_streak' => 8,
                'longest_streak' => 10,
                'last_activity_date' => '2026-04-29',
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Meta')->first(),
                'title' => 'Meta Frontend Interview',
                'target_role' => 'Frontend Engineer',
                'interview_date' => '2026-06-01',
                'start_date' => '2026-04-15',
                'status' => 'active',
                'total_tasks' => 45,
                'completed_tasks' => 15,
                'completion_percentage' => 33,
                'current_streak' => 3,
                'longest_streak' => 5,
                'last_activity_date' => '2026-04-27',
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Microsoft')->first(),
                'title' => 'Microsoft Interview Prep',
                'target_role' => 'Software Engineer',
                'interview_date' => '2026-05-10',
                'start_date' => '2026-04-05',
                'status' => 'active',
                'total_tasks' => 40,
                'completed_tasks' => 30,
                'completion_percentage' => 75,
                'current_streak' => 10,
                'longest_streak' => 12,
                'last_activity_date' => '2026-04-29',
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Apple')->first(),
                'title' => 'Apple iOS Developer Prep',
                'target_role' => 'iOS Engineer',
                'interview_date' => '2026-06-15',
                'start_date' => '2026-04-20',
                'status' => 'active',
                'total_tasks' => 55,
                'completed_tasks' => 10,
                'completion_percentage' => 18,
                'current_streak' => 2,
                'longest_streak' => 4,
                'last_activity_date' => '2026-04-26',
            ],
            [
                'user' => $users->random(),
                'company' => null,
                'title' => 'General Interview Prep',
                'target_role' => 'Software Engineer',
                'interview_date' => null,
                'start_date' => '2026-04-01',
                'status' => 'active',
                'total_tasks' => 100,
                'completed_tasks' => 65,
                'completion_percentage' => 65,
                'current_streak' => 15,
                'longest_streak' => 20,
                'last_activity_date' => '2026-04-29',
            ],
            [
                'user' => $users->random(),
                'company' => $companies->where('name', 'Google')->first(),
                'title' => 'Google System Design',
                'target_role' => 'Senior Software Engineer',
                'interview_date' => '2026-04-20',
                'start_date' => '2026-03-15',
                'status' => 'completed',
                'total_tasks' => 30,
                'completed_tasks' => 30,
                'completion_percentage' => 100,
                'current_streak' => 0,
                'longest_streak' => 25,
                'last_activity_date' => '2026-04-20',
            ],
        ];

        foreach ($plans as $planData) {
            $user = $planData['user'];
            $company = $planData['company'];
            unset($planData['user'], $planData['company']);

            PreparationPlan::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $planData['title'],
                ],
                array_merge($planData, [
                    'user_id' => $user->id,
                    'company_id' => $company ? $company->id : null,
                ])
            );
        }

        $this->command->info('Preparation plans seeded: ' . count($plans) . ' plans.');
    }
}