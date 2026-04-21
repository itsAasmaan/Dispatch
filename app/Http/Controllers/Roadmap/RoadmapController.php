<?php

namespace App\Http\Controllers\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roadmap\UpdateTopicProgressRequest;
use App\Models\CandidateTopicProgress;
use App\Models\Roadmap;
use App\Models\RoadmapEnrollment;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    // GET /api/roadmaps
    public function index(Request $request): JsonResponse
    {
        $roadmaps = Roadmap::active()
            ->when($request->role, fn($q) => $q->byRole($request->role))
            ->when($request->level, fn($q) => $q->byLevel($request->level))
            ->withCount('topics')
            ->orderBy('enrolled_count', 'desc')
            ->get();

        return $this->success($roadmaps);
    }

    // GET /api/roadmaps/{roadmap}
    public function show(Roadmap $roadmap): JsonResponse
    {
        $roadmap->load('topics');

        $data = $roadmap->toArray();
        // If authenticated attach progress per topic
        if (auth()->check()) {
            $user = auth()->user();
            $progress = CandidateTopicProgress::where('user_id', $user->id)
                ->where('roadmap_id', $roadmap->id)
                ->get()
                ->keyBy('topic_id');

            $data['topics'] = collect($roadmap->topics)->map(function ($topic) use ($progress) {
                $topicProgress = $progress->get($topic->id);
                $topic['progress'] = $topicProgress ? [
                    'status' => $topicProgress->status,
                    'progress_percentage' => $topicProgress->progress_percentage,
                    'started_at' => $topicProgress->started_at,
                    'completed_at' => $topicProgress->completed_at,
                ] : [
                    'status' => 'not_started',
                    'progress_percentage' => 0,
                    'started_at' => null,
                    'completed_at' => null,
                ];

                return $topic;
            });

            // Enrollment status
            $enrollment = $roadmap->enrollmentFor($user);
            $data['enrollment'] = $enrollment ? [
                'status' => $enrollment->status,
                'completion_percentage' => $enrollment->completion_percentage,
                'enrolled_at' => $enrollment->enrolled_at,
            ] : null;
        }

        return $this->success($data);
    }

    // POST /api/roadmaps/{roadmap}/enroll
    public function enroll(Roadmap $roadmap): JsonResponse
    {
        $user = auth()->user();

        if ($roadmap->isEnrolledBy($user)) {
            return $this->error('You are already enrolled in this roadmap', 409);
        }

        RoadmapEnrollment::create([
            'user_id' => $user->id,
            'roadmap_id' => $roadmap->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $roadmap->increment('enrolled_count');

        return $this->success(null, "Enrolled in {$roadmap->title} successfully");
    }

    // DELETE /api/roadmaps/{roadmap}/enroll
    public function unenroll(Roadmap $roadmap): JsonResponse
    {
        $user = auth()->user();
        $enrollment = $roadmap->enrollmentFor($user);

        if (!$enrollment) {
            return $this->error('You are not enrolled in this roadmap', 409);
        }

        $enrollment->update(['status' => 'dropped']);
        $roadmap->decrement('enrolled_count');

        return $this->success(null, "Unenrolled from {$roadmap->title}");
    }

    // PUT /api/roadmaps/{roadmap}/topics/{topic}/progress
    public function updateTopicProgress(
        UpdateTopicProgressRequest $request,
        Roadmap $roadmap,
        Topic $topic
    ): JsonResponse {
        $user = auth()->user();

        // Must be enrolled to track progress
        if (!$roadmap->isEnrolledBy($user)) {
            return $this->error('You must be enrolled in this roadmap to track progress', 403);
        }

        $progress = CandidateTopicProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'topic_id' => $topic->id,
                'roadmap_id' => $roadmap->id,
            ],
            [
                'status' => $request->status,
                'progress_percentage' => $request->progress_percentage
                    ?? ($request->status === 'completed' ? 100 : 50),
                'notes' => $request->notes,
                'started_at' => $request->status !== 'not_started'
                    ? now()
                    : null,
                'completed_at' => $request->status === 'completed'
                    ? now()
                    : null,
            ]
        );

        // Recalculate overall roadmap progress
        $enrollment = $roadmap->enrollmentFor($user);
        $enrollment?->recalculateProgress();

        return $this->success($progress, 'Topic progress updated');
    }

    // GET /api/roadmaps/my-progress
    public function myProgress(): JsonResponse
    {
        $user = auth()->user();

        $enrollments = RoadmapEnrollment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('roadmap:id,title,slug,icon,target_role,level')
            ->get()
            ->map(function ($enrollment) use ($user) {
                $completedTopics = CandidateTopicProgress::where('user_id', $user->id)
                    ->where('roadmap_id', $enrollment->roadmap_id)
                    ->where('status', 'completed')
                    ->count();

                return [
                    'roadmap' => $enrollment->roadmap,
                    'status' => $enrollment->status,
                    'completion_percentage' => $enrollment->completion_percentage,
                    'completed_topics' => $completedTopics,
                    'enrolled_at' => $enrollment->enrolled_at,
                ];
            });

        return $this->success($enrollments);
    }

    // GET /api/topics
    public function topics(Request $request): JsonResponse
    {
        $topics = Topic::active()
            ->when($request->category, fn($q) => $q->byCategory($request->category))
            ->when($request->difficulty, fn($q) => $q->byDifficulty($request->difficulty))
            ->orderBy('title')
            ->get();

        return $this->success($topics);
    }
}