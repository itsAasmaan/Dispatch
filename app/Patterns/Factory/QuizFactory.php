<?php

namespace App\Patterns\Factory;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class QuizFactory
{
    /**
     * Create a quiz and attach questions based on type
     */
    public static function create(array $config): Quiz
    {
        // Validate quiz type
        $supportedTypes = ['topic', 'company', 'role', 'mixed', 'mock_interview'];

        if (!in_array($config['type'], $supportedTypes)) {
            throw new InvalidArgumentException(
                "Unsupported quiz type: {$config['type']}. Supported: " . implode(', ', $supportedTypes)
            );
        }

        $questions = self::resolveQuestions($config);

        if ($questions->isEmpty()) {
            throw new \RuntimeException(
                "Not enough questions available to generate a {$config['type']} quiz."
            );
        }

        // Create the quiz record
        $quiz = Quiz::create([
            'title' => self::generateTitle($config),
            'description' => self::generateDescription($config),
            'type' => $config['type'],
            'topic' => $config['topic'] ?? null,
            'company' => $config['company'] ?? null,
            'role' => $config['role'] ?? null,
            'difficulty' => $config['difficulty'] ?? 'mixed',
            'total_questions' => $questions->count(),
            'time_limit_minutes' => $config['time_limit'] ?? self::defaultTimeLimit($config['type']),
            'is_timed' => $config['is_timed'] ?? true,
            'created_by' => $config['created_by'] ?? null,
        ]);

        self::attachQuestions($quiz, $questions);

        return $quiz->load('questions');
    }

    private static function resolveQuestions(array $config): Collection
    {
        $limit = $config['total_questions'] ?? 10;
        $difficulty = $config['difficulty'] ?? null;

        return match ($config['type']) {
            'topic' => self::getTopicQuestions($config['topic'], $difficulty, $limit),
            'company' => self::getCompanyQuestions($config['company'], $difficulty, $limit),
            'role' => self::getRoleQuestions($config['role'], $difficulty, $limit),
            'mixed' => self::getMixedQuestions($difficulty, $limit),
            'mock_interview' => self::getMockInterviewQuestions($config, $limit),
        };
    }

    private static function getTopicQuestions(string $topic, ?string $difficulty, int $limit): Collection
    {
        return Question::approved()
            ->whereJsonContains('tags', $topic)
            ->when($difficulty && $difficulty !== 'mixed', fn($q) => $q->byDifficulty($difficulty))
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    private static function getCompanyQuestions(string $company, ?string $difficulty, int $limit): Collection
    {
        return Question::approved()
            ->byCompany($company)
            ->when($difficulty && $difficulty !== 'mixed', fn($q) => $q->byDifficulty($difficulty))
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    private static function getRoleQuestions(string $role, ?string $difficulty, int $limit): Collection
    {
        $categoryMap = [
            'frontend' => ['frontend', 'dsa'],
            'backend' => ['backend', 'dsa', 'database'],
            'fullstack' => ['frontend', 'backend', 'database'],
            'devops' => ['devops', 'backend'],
            'data_engineer' => ['database', 'backend', 'dsa'],
            'mobile' => ['frontend', 'dsa'],
        ];

        $categories = $categoryMap[$role] ?? ['dsa', 'system_design', 'backend'];

        return Question::approved()
            ->whereIn('category', $categories)
            ->when($difficulty && $difficulty !== 'mixed', fn($q) => $q->byDifficulty($difficulty))
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    private static function getMixedQuestions(?string $difficulty, int $limit): Collection
    {
        return Question::approved()
            ->when($difficulty && $difficulty !== 'mixed', fn($q) => $q->byDifficulty($difficulty))
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    private static function getMockInterviewQuestions(array $config, int $limit): Collection
    {
        $perCategory = max(1, intdiv($limit, 3));

        $dsa = Question::approved()
            ->byCategory('dsa')
            ->inRandomOrder()
            ->limit($perCategory)
            ->get();

        $systemDesign = Question::approved()
            ->byCategory('system_design')
            ->inRandomOrder()
            ->limit($perCategory)
            ->get();

        $behavioural = Question::approved()
            ->byCategory('behavioural')
            ->inRandomOrder()
            ->limit($perCategory)
            ->get();

        return $dsa->merge($systemDesign)->merge($behavioural)->shuffle();
    }

    private static function attachQuestions(Quiz $quiz, Collection $questions): void
    {
        $order = 1;

        foreach ($questions as $question) {
            $points = match ($question->difficulty) {
                'easy' => 1,
                'medium' => 2,
                'hard' => 3,
                default => 1,
            };

            $quiz->questions()->attach($question->id, [
                'order' => $order++,
                'points' => $points,
            ]);
        }
    }

    private static function generateTitle(array $config): string
    {
        return match ($config['type']) {
            'topic' => ucfirst($config['topic'] ?? 'Topic') . ' Quiz',
            'company' => ($config['company'] ?? 'Company') . ' Interview Quiz',
            'role' => ucfirst($config['role'] ?? 'Role') . ' Engineer Quiz',
            'mixed' => 'Mixed Practice Quiz',
            'mock_interview' => 'Mock Interview Simulation',
        };
    }

    private static function generateDescription(array $config): string
    {
        return match ($config['type']) {
            'topic' => "Practice questions focused on {$config['topic']}.",
            'company' => "Questions frequently asked at {$config['company']}.",
            'role' => "Prepare for a {$config['role']} engineer role.",
            'mixed' => 'A mix of questions across all categories.',
            'mock_interview' => 'Simulates a real interview with DSA, System Design, and Behavioural questions.',
        };
    }

    private static function defaultTimeLimit(string $type): int
    {
        return match ($type) {
            'mock_interview' => 60,
            'topic' => 20,
            'company' => 30,
            'role' => 30,
            'mixed' => 25,
            default => 30,
        };
    }
}