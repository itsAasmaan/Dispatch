<?php

namespace Database\Seeders;

use App\Models\SalaryInsight;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class SalaryInsightSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('candidate')->get();
        $companies = Company::all();

        if ($users->isEmpty() || $companies->isEmpty()) {
            $this->command->warn('No users or companies found. Please run UserSeeder and CompanySeeder first.');
            return;
        }

        $salaryInsights = [
            // Google Salaries
            [
                'company' => $companies->where('name', 'Google')->first(),
                'role_title' => 'Software Engineer L3',
                'role_type' => 'full-time',
                'base_salary' => 150000,
                'bonus' => 25000,
                'stock' => 80000,
                'total_compensation' => 255000,
                'location' => 'Mountain View, CA',
                'years_of_experience' => 2,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            [
                'company' => $companies->where('name', 'Google')->first(),
                'role_title' => 'Software Engineer L4',
                'role_type' => 'full-time',
                'base_salary' => 180000,
                'bonus' => 35000,
                'stock' => 150000,
                'total_compensation' => 365000,
                'location' => 'New York, NY',
                'years_of_experience' => 4,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            [
                'company' => $companies->where('name', 'Google')->first(),
                'role_title' => 'Frontend Engineer',
                'role_type' => 'full-time',
                'base_salary' => 160000,
                'bonus' => 30000,
                'stock' => 100000,
                'total_compensation' => 290000,
                'location' => 'Seattle, WA',
                'years_of_experience' => 3,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            // Amazon Salaries
            [
                'company' => $companies->where('name', 'Amazon')->first(),
                'role_title' => 'SDE II',
                'role_type' => 'full-time',
                'base_salary' => 155000,
                'bonus' => 40000,
                'stock' => 80000,
                'total_compensation' => 275000,
                'location' => 'Seattle, WA',
                'years_of_experience' => 3,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            [
                'company' => $companies->where('name', 'Amazon')->first(),
                'role_title' => 'SDE I',
                'role_type' => 'full-time',
                'base_salary' => 130000,
                'bonus' => 25000,
                'stock' => 55000,
                'total_compensation' => 210000,
                'location' => 'Austin, TX',
                'years_of_experience' => 1,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            [
                'company' => $companies->where('name', 'Amazon')->first(),
                'role_title' => 'Software Engineer Intern',
                'role_type' => 'internship',
                'base_salary' => 12000,
                'bonus' => 0,
                'stock' => 0,
                'total_compensation' => 12000,
                'location' => 'Seattle, WA',
                'years_of_experience' => 0,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            // Meta Salaries
            [
                'company' => $companies->where('name', 'Meta')->first(),
                'role_title' => 'Software Engineer',
                'role_type' => 'full-time',
                'base_salary' => 165000,
                'bonus' => 30000,
                'stock' => 120000,
                'total_compensation' => 315000,
                'location' => 'Menlo Park, CA',
                'years_of_experience' => 3,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            [
                'company' => $companies->where('name', 'Meta')->first(),
                'role_title' => 'Frontend Engineer',
                'role_type' => 'full-time',
                'base_salary' => 170000,
                'bonus' => 35000,
                'stock' => 130000,
                'total_compensation' => 335000,
                'location' => 'Menlo Park, CA',
                'years_of_experience' => 4,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            // Microsoft Salaries
            [
                'company' => $companies->where('name', 'Microsoft')->first(),
                'role_title' => 'Software Engineer',
                'role_type' => 'full-time',
                'base_salary' => 140000,
                'bonus' => 20000,
                'stock' => 60000,
                'total_compensation' => 220000,
                'location' => 'Redmond, WA',
                'years_of_experience' => 2,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            [
                'company' => $companies->where('name', 'Microsoft')->first(),
                'role_title' => 'Senior Software Engineer',
                'role_type' => 'full-time',
                'base_salary' => 175000,
                'bonus' => 30000,
                'stock' => 100000,
                'total_compensation' => 305000,
                'location' => 'Redmond, WA',
                'years_of_experience' => 5,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            // Apple Salaries
            [
                'company' => $companies->where('name', 'Apple')->first(),
                'role_title' => 'iOS Engineer',
                'role_type' => 'full-time',
                'base_salary' => 180000,
                'bonus' => 40000,
                'stock' => 150000,
                'total_compensation' => 370000,
                'location' => 'Cupertino, CA',
                'years_of_experience' => 4,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
            [
                'company' => $companies->where('name', 'Apple')->first(),
                'role_title' => 'Software Engineer',
                'role_type' => 'full-time',
                'base_salary' => 165000,
                'bonus' => 35000,
                'stock' => 120000,
                'total_compensation' => 320000,
                'location' => 'Cupertino, CA',
                'years_of_experience' => 3,
                'outcome' => 'offer_received',
                'offer_year' => 2026,
            ],
        ];

        foreach ($salaryInsights as $insight) {
            $company = $insight['company'];
            unset($insight['company']);

            // Random user for anonymous salary data
            $user = $users->random();

            SalaryInsight::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'role_title' => $insight['role_title'],
                    'user_id' => $user->id,
                ],
                array_merge($insight, [
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'currency' => 'USD',
                    'is_anonymous' => true,
                ])
            );
        }

        $this->command->info('Salary insights seeded: ' . count($salaryInsights) . ' salary records.');
    }
}