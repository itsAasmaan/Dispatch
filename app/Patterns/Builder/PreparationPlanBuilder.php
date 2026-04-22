<?php

namespace App\Patterns\Builder;

use App\Models\Company;
use App\Models\PreparationPlan;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;

class PreparationPlanBuilder
{
    private User $user;
    private string $targetRole;
    private ?int $companyId = 0;
    private ?Carbon $interviewDate = null;
    private Carbon $startDate;
    private int $totalDays = 14;
    private array $tasks = [];

    public function forUser(User $user): static
    {
        $this->user = $user;
        $this->startDate = today();

        return $this;
    }

    public function targetRole(string $targetRole): static
    {
        $this->targetRole = $targetRole;

        return $this;
    }

    public function forCompany(int $companyId): static
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function interviewOn(string $date): static
    {
        $this->interviewDate = Carbon::parse($date);
        $this->totalDays = max(7, today()->diffInDays($this->interviewDate));
        return $this;
    }

    public function startingFrom(string $date): static
    {
        $this->startDate = Carbon::parse($date);

        return $this;
    }

    public function build(): PreparationPlan
    {
        $company = $this->companyId ? Company::find($this->companyId) : null;

        $title = $company ? "Preparing for {$company->name} — {$this->targetRole}" : "Preparing for {$this->targetRole} Interview";

        $plan = PreparationPlan::create([
            'user_id' => $this->user->id,
            'company_id' => $this->companyId,
            'title' => $title,
            'target_role' => $this->targetRole,
            'interview_date' => $this->interviewDate,
            'start_date' => $this->startDate,
            'status' => 'active',
        ]);

        $this->generateTasks($plan);
        $plan->update(['total_tasks' => count($this->tasks)]);

        return $plan->load('tasks');
    }

    private function generateTasks(PreparationPlan $plan): void
    {
        $dayNumber = 1;
        $topics = $this->getTopicsForRole($this->targetRole);
        $dueDate = $this->startDate->copy();

        // Phase 1: Study topics (60% of days)
        $studyDays = (int) ceil($this->totalDays * 0.6);
        foreach ($topics->take($studyDays) as $topic) {
            $this->addTask($plan, [
                'title' => "Study: {$topic->title}",
                'description' => "Go through {$topic->title} concepts and practice examples.",
                'type' => 'study_topic',
                'topic_id' => $topic->id,
                'due_date' => $dueDate->copy(),
                'day_number' => $dayNumber,
            ]);

            // Every 2 study days add a quiz task
            if ($dayNumber % 2 === 0) {
                $this->addTask($plan, [
                    'title' => "Quiz: {$topic->category} practice",
                    'description' => "Take a quick quiz to test your understanding.",
                    'type' => 'take_quiz',
                    'topic_id' => $topic->id,
                    'due_date' => $dueDate->copy(),
                    'day_number' => $dayNumber,
                ]);
            }

            $dayNumber++;
            $dueDate->addDay();
        }

        // Phase 2: Practice questions (20% of days)
        $practicedays = (int) ceil($this->totalDays * 0.2);
        for ($i = 0; $i < $practicedays; $i++) {
            $this->addTask($plan, [
                'title' => 'Practice: Solve interview questions',
                'description' => 'Solve 5-10 questions from the question bank.',
                'type' => 'solve_questions',
                'due_date' => $dueDate->copy(),
                'day_number' => $dayNumber,
            ]);

            $dayNumber++;
            $dueDate->addDay();
        }

        // Phase 3: Read interview experiences (10% of days)
        $experienceDays = (int) ceil($this->totalDays * 0.1);
        for ($i = 0; $i < $experienceDays; $i++) {
            $companyName = $this->companyId ? Company::find($this->companyId)?->name ?? 'target company' : 'target company';
            $this->addTask($plan, [
                'title' => "Read: {$companyName} interview experiences",
                'description' => "Read recent interview experiences to understand the process.",
                'type' => 'read_experience',
                'due_date' => $dueDate->copy(),
                'day_number' => $dayNumber,
            ]);

            $dayNumber++;
            $dueDate->addDay();
        }

        // Phase 4: Mock interview (final days)
        $mockDays = max(1, (int) ceil($this->totalDays * 0.1));
        for ($i = 0; $i < $mockDays; $i++) {
            $this->addTask($plan, [
                'title' => 'Mock Interview Simulation',
                'description' => 'Complete a full mock interview to simulate the real experience.',
                'type' => 'mock_interview',
                'due_date' => $dueDate->copy(),
                'day_number' => $dayNumber,
            ]);

            $dayNumber++;
            $dueDate->addDay();
        }
    }

    private function addTask(PreparationPlan $plan, array $data): void
    {
        $task = $plan->tasks()->create($data);
        $this->tasks[] = $task;
    }

    private function getTopicsForRole(string $role)
    {
        $categoryMap = [
            'frontend' => ['frontend', 'dsa'],
            'backend' => ['backend', 'dsa', 'database', 'system_design'],
            'fullstack' => ['frontend', 'backend', 'database', 'system_design'],
            'devops' => ['devops', 'backend', 'system_design'],
            'data_engineer' => ['database', 'backend', 'dsa'],
            'mobile' => ['frontend', 'dsa'],
        ];

        $categories = $categoryMap[$role] ?? ['dsa', 'system_design', 'backend'];

        return Topic::active()->whereIn('category', $categories)->orderBy('difficulty')->get();
    }
}