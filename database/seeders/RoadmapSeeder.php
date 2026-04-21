<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class RoadmapSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            // DSA Topics
            ['title' => 'Arrays & Strings', 'category' => 'dsa', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 90],
            ['title' => 'Linked Lists', 'category' => 'dsa', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 60],
            ['title' => 'Stacks & Queues', 'category' => 'dsa', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 60],
            ['title' => 'Trees & Graphs', 'category' => 'dsa', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 120],
            ['title' => 'Dynamic Programming', 'category' => 'dsa', 'difficulty' => 'advanced', 'estimated_duration_minutes' => 180],
            ['title' => 'Sorting & Searching', 'category' => 'dsa', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 90],

            // System Design Topics
            ['title' => 'System Design Basics', 'category' => 'system_design', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 90],
            ['title' => 'Databases & Caching', 'category' => 'system_design', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 120],
            ['title' => 'Microservices', 'category' => 'system_design', 'difficulty' => 'advanced', 'estimated_duration_minutes' => 120],
            ['title' => 'API Design', 'category' => 'system_design', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 90],

            // Backend Topics
            ['title' => 'OOP Concepts', 'category' => 'backend', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 60],
            ['title' => 'Design Patterns', 'category' => 'backend', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 120],
            ['title' => 'REST APIs', 'category' => 'backend', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 60],
            ['title' => 'Database Design', 'category' => 'backend', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 90],

            // Frontend Topics
            ['title' => 'HTML & CSS Basics', 'category' => 'frontend', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 60],
            ['title' => 'JavaScript Fundamentals', 'category' => 'frontend', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 120],
            ['title' => 'React Fundamentals', 'category' => 'frontend', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 120],
            ['title' => 'State Management', 'category' => 'frontend', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 90],

            // Behavioural Topics
            ['title' => 'STAR Method', 'category' => 'behavioural', 'difficulty' => 'beginner', 'estimated_duration_minutes' => 45],
            ['title' => 'Leadership Questions', 'category' => 'behavioural', 'difficulty' => 'intermediate', 'estimated_duration_minutes' => 60],
        ];

        $createdTopics = [];
        foreach ($topics as $topic) {
            $createdTopics[$topic['title']] = Topic::firstOrCreate(
                ['title' => $topic['title']],
                $topic
            );
        }

        // Backend Engineer Roadmap
        $backendRoadmap = Roadmap::firstOrCreate(
            ['slug' => 'backend-engineer'],
            [
                'title' => 'Backend Engineer',
                'description' => 'Complete roadmap to become a backend engineer.',
                'icon' => '⚙️',
                'target_role' => 'backend',
                'level' => 'intermediate',
                'estimated_hours' => 40,
            ]
        );

        $backendTopics = [
            'OOP Concepts' => ['order' => 1, 'is_required' => true],
            'Design Patterns' => ['order' => 2, 'is_required' => true],
            'REST APIs' => ['order' => 3, 'is_required' => true],
            'Database Design' => ['order' => 4, 'is_required' => true],
            'Arrays & Strings' => ['order' => 5, 'is_required' => true],
            'Trees & Graphs' => ['order' => 6, 'is_required' => true],
            'Dynamic Programming' => ['order' => 7, 'is_required' => false],
            'System Design Basics' => ['order' => 8, 'is_required' => true],
            'Databases & Caching' => ['order' => 9, 'is_required' => true],
            'Microservices' => ['order' => 10, 'is_required' => false],
            'STAR Method' => ['order' => 11, 'is_required' => true],
        ];

        foreach ($backendTopics as $topicTitle => $pivot) {
            if (isset($createdTopics[$topicTitle])) {
                $backendRoadmap->topics()->syncWithoutDetaching([
                    $createdTopics[$topicTitle]->id => $pivot,
                ]);
            }
        }

        // Frontend Engineer Roadmap
        $frontendRoadmap = Roadmap::firstOrCreate(
            ['slug' => 'frontend-engineer'],
            [
                'title' => 'Frontend Engineer',
                'description' => 'Complete roadmap to become a frontend engineer.',
                'icon' => '🎨',
                'target_role' => 'frontend',
                'level' => 'intermediate',
                'estimated_hours' => 35,
            ]
        );

        $frontendTopics = [
            'HTML & CSS Basics' => ['order' => 1, 'is_required' => true],
            'JavaScript Fundamentals' => ['order' => 2, 'is_required' => true],
            'React Fundamentals' => ['order' => 3, 'is_required' => true],
            'State Management' => ['order' => 4, 'is_required' => true],
            'Arrays & Strings' => ['order' => 5, 'is_required' => true],
            'System Design Basics' => ['order' => 6, 'is_required' => false],
            'API Design' => ['order' => 7, 'is_required' => true],
            'STAR Method' => ['order' => 8, 'is_required' => true],
        ];

        foreach ($frontendTopics as $topicTitle => $pivot) {
            if (isset($createdTopics[$topicTitle])) {
                $frontendRoadmap->topics()->syncWithoutDetaching([
                    $createdTopics[$topicTitle]->id => $pivot,
                ]);
            }
        }

        // Fullstack Roadmap
        $fullstackRoadmap = Roadmap::firstOrCreate(
            ['slug' => 'fullstack-engineer'],
            [
                'title' => 'Fullstack Engineer',
                'description' => 'Complete roadmap to become a fullstack engineer.',
                'icon' => '🚀',
                'target_role' => 'fullstack',
                'level' => 'advanced',
                'estimated_hours' => 60,
            ]
        );

        $fullstackTopics = [
            'HTML & CSS Basics' => ['order' => 1, 'is_required' => true],
            'JavaScript Fundamentals' => ['order' => 2, 'is_required' => true],
            'React Fundamentals' => ['order' => 3, 'is_required' => true],
            'OOP Concepts' => ['order' => 4, 'is_required' => true],
            'REST APIs' => ['order' => 5, 'is_required' => true],
            'Database Design' => ['order' => 6, 'is_required' => true],
            'Arrays & Strings' => ['order' => 7, 'is_required' => true],
            'System Design Basics' => ['order' => 8, 'is_required' => true],
            'Databases & Caching' => ['order' => 9, 'is_required' => false],
            'STAR Method' => ['order' => 10, 'is_required' => true],
        ];

        foreach ($fullstackTopics as $topicTitle => $pivot) {
            if (isset($createdTopics[$topicTitle])) {
                $fullstackRoadmap->topics()->syncWithoutDetaching([
                    $createdTopics[$topicTitle]->id => $pivot,
                ]);
            }
        }

        $this->command->info('Roadmaps seeded: Backend, Frontend, Fullstack ✅');
    }
}