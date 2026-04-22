<?php

namespace App\Http\Controllers\PreparationPlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreparationPlan\CreatePlanRequest;
use App\Models\PreparationPlan;
use App\Models\PreparationTask;
use App\Patterns\Builder\PreparationPlanBuilder;
use Illuminate\Http\JsonResponse;

class PreparationPlanController extends Controller
{
    // GET /api/preparation-plans
    public function index(): JsonResponse
    {
        $plans = PreparationPlan::where('user_id', auth()->id())
            ->with('company:id,name,slug,logo')
            ->withCount('tasks')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->success($plans);
    }

    // GET /api/preparation-plans/{plan}
    public function show(PreparationPlan $plan): JsonResponse
    {
        if ($plan->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        $plan->load([
            'company:id,name,slug,logo',
            'tasks.topic:id,title,slug,category',
            'tasks.quiz:id,title,type',
        ]);

        $data = $plan->toArray();
        $data['days_until_interview'] = $plan->daysUntilInterview();
        $data['today_tasks'] = $plan->todayTasks()->with('topic', 'quiz')->get();

        return $this->success($data);
    }

    // POST /api/preparation-plans
    public function store(CreatePlanRequest $request): JsonResponse
    {
        $builder = (new PreparationPlanBuilder())
            ->forUser(auth()->user())
            ->targetRole($request->target_role);

        if ($request->company_id) {
            $builder->forCompany($request->company_id);
        }

        if ($request->interview_date) {
            $builder->interviewOn($request->interview_date);
        }

        if ($request->start_date) {
            $builder->startingFrom($request->start_date);
        }

        $plan = $builder->build();

        return $this->created($plan, 'Preparation plan created successfully');
    }

    // DELETE /api/preparation-plans/{plan}
    public function destroy(PreparationPlan $plan): JsonResponse
    {
        if ($plan->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        $plan->delete();

        return $this->success(null, 'Preparation plan deleted');
    }

    // POST /api/preparation-plans/tasks/{task}/complete
    public function completeTask(PreparationTask $task): JsonResponse
    {
        if ($task->plan->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if (!$task->isPending()) {
            return $this->error('Task is already ' . $task->status, 409);
        }

        $task->markCompleted();

        return $this->success([
            'task' => $task,
            'streak' => $task->plan->fresh()->current_streak,
        ], 'Task completed 🔥');
    }

    // POST /api/preparation-plans/tasks/{task}/skip
    public function skipTask(PreparationTask $task): JsonResponse
    {
        if ($task->plan->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if (!$task->isPending()) {
            return $this->error('Task is already ' . $task->status, 409);
        }

        $task->markSkipped();

        return $this->success(null, 'Task skipped');
    }

    // GET /api/preparation-plans/{plan}/today
    public function todayTasks(PreparationPlan $plan): JsonResponse
    {
        if ($plan->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        $tasks = $plan->todayTasks()
            ->with(['topic:id,title,slug', 'quiz:id,title,type'])
            ->get();

        return $this->success([
            'date' => today()->toDateString(),
            'tasks' => $tasks,
            'current_streak' => $plan->current_streak,
        ]);
    }
}