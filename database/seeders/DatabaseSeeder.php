<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CompanySeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RoadmapSeeder;
use Database\Seeders\InterviewSeeder;
use Database\Seeders\QuestionSeeder;
use Database\Seeders\QuizSeeder;
use Database\Seeders\SalaryInsightSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\PreparationPlanSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            RoadmapSeeder::class,
            InterviewSeeder::class,
            QuestionSeeder::class,
            QuizSeeder::class,
            SalaryInsightSeeder::class,
            CommentSeeder::class,
            PreparationPlanSeeder::class,
        ]);
    }
}