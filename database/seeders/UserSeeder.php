<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $candidates = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'username' => 'johnsmith',
                'bio' => 'Full-stack developer with 3 years of experience.',
                'current_role' => 'Software Engineer',
                'current_company' => 'Tech Startup Inc',
                'years_of_experience' => 3,
                'github_url' => 'https://github.com/johnsmith',
                'linkedin_url' => 'https://linkedin.com/in/johnsmith',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'username' => 'sarahjohnson',
                'bio' => 'Backend engineer passionate about system design.',
                'current_role' => 'Backend Developer',
                'current_company' => 'CloudTech Solutions',
                'years_of_experience' => 5,
                'github_url' => 'https://github.com/sarahj',
                'linkedin_url' => 'https://linkedin.com/in/sarahjohnson',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'username' => 'michaelchen',
                'bio' => 'Frontend specialist with expertise in React and Vue.',
                'current_role' => 'Frontend Engineer',
                'current_company' => 'WebFlow Agency',
                'years_of_experience' => 2,
                'github_url' => 'https://github.com/mchen',
                'linkedin_url' => 'https://linkedin.com/in/michaelchen',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'username' => 'emilydavis',
                'bio' => 'DevOps engineer with strong AWS and Kubernetes skills.',
                'current_role' => 'DevOps Engineer',
                'current_company' => 'InfraScale',
                'years_of_experience' => 4,
                'github_url' => 'https://github.com/emilyd',
                'linkedin_url' => 'https://linkedin.com/in/emilydavis',
            ],
            [
                'name' => 'Alex Rodriguez',
                'email' => 'alex.rodriguez@example.com',
                'username' => 'alexrodriguez',
                'bio' => 'Data engineer focused on building scalable data pipelines.',
                'current_role' => 'Data Engineer',
                'current_company' => 'DataFlow Corp',
                'years_of_experience' => 3,
                'github_url' => 'https://github.com/alexrod',
                'linkedin_url' => 'https://linkedin.com/in/alexrodriguez',
            ],
            [
                'name' => 'Lisa Wang',
                'email' => 'lisa.wang@example.com',
                'username' => 'lisawang',
                'bio' => 'Mobile developer specializing in React Native and Flutter.',
                'current_role' => 'Mobile Developer',
                'current_company' => 'AppCraft',
                'years_of_experience' => 2,
                'github_url' => 'https://github.com/lwang',
                'linkedin_url' => 'https://linkedin.com/in/lisawang',
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@example.com',
                'username' => 'davidkim',
                'bio' => 'Full-stack developer transitioning to backend focus.',
                'current_role' => 'Software Developer',
                'current_company' => 'StartupXYZ',
                'years_of_experience' => 1,
                'github_url' => 'https://github.com/dkim',
                'linkedin_url' => 'https://linkedin.com/in/davidkim',
            ],
            [
                'name' => 'Jennifer Lee',
                'email' => 'jennifer.lee@example.com',
                'username' => 'jenniferlee',
                'bio' => 'Experienced engineer preparing for senior roles.',
                'current_role' => 'Senior Software Engineer',
                'current_company' => 'Enterprise Solutions',
                'years_of_experience' => 7,
                'github_url' => 'https://github.com/jlee',
                'linkedin_url' => 'https://linkedin.com/in/jenniferlee',
            ],
        ];

        foreach ($candidates as $candidate) {
            $user = User::firstOrCreate(
                ['email' => $candidate['email']],
                array_merge($candidate, [
                    'password' => Hash::make('password123'),
                ])
            );
            $user->assignRole('candidate');
        }

        $this->command->info('Candidate users seeded: ' . count($candidates) . ' users.');
    }
}