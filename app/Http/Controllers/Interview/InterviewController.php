<?php

namespace App\Http\Controllers\Interview;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\StoreInterviewRequest;
use App\Http\Requests\Interview\UpdateInterviewRequest;
use App\Models\Interview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    // GET /api/interviews
    public function index(Request $request): JsonResponse
    {
        $interviews = Interview::published()
            ->with(['user:id,name,username,avatar', 'company:id,name,slug,logo'])
            ->when($request->company_id, fn($q) => $q->byCompany($request->company_id))
            ->when($request->outcome, fn($q) => $q->byOutcome($request->outcome))
            ->when($request->difficulty, fn($q) => $q->byDifficulty($request->difficulty))
            ->when($request->role_title, fn($q) => $q->where('role_title', 'like', "%{$request->role_title}%"))
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return $this->success($interviews);
    }

    // GET /api/interviews/{interview}
    public function show(Interview $interview): JsonResponse
    {
        // Only show published interviews to public
        if ($interview->status !== 'published' && auth()->id() !== $interview->user_id) {
            return $this->notFound();
        }

        $interview->load([
            'user:id,name,username,avatar',
            'company:id,name,slug,logo',
            'rounds',
        ]);

        // Increment view count
        $interview->increment('view_count');

        // Check if authed user has upvoted or bookmarked
        if (auth()->check()) {
            $interview->is_upvoted = $interview->isUpvotedBy(auth()->user());
            $interview->is_bookmarked = $interview->isBookmarkedBy(auth()->user());
        }

        return $this->success($interview);
    }

    // POST /api/interviews
    public function store(StoreInterviewRequest $request): JsonResponse
    {
        $interview = Interview::create([
            ...$request->except('rounds'),
            'user_id' => auth()->id(),
        ]);

        // Create rounds
        foreach ($request->rounds as $round) {
            $interview->rounds()->create($round);
        }

        // If published immediately update company stats
        if ($interview->status === 'published') {
            $interview->company->incrementInterviewCount();
            $interview->update(['published_at' => now()]);
        }

        $interview->load('rounds');

        return $this->created($interview, 'Interview experience shared successfully');
    }

    // PUT /api/interviews/{interview}
    public function update(UpdateInterviewRequest $request, Interview $interview): JsonResponse
    {
        // Only owner can update
        if ($interview->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        $interview->update($request->validated());

        return $this->success($interview, 'Interview experience updated successfully');
    }

    // DELETE /api/interviews/{interview}
    public function destroy(Interview $interview): JsonResponse
    {
        // Owner or admin can delete
        if ($interview->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return $this->forbidden();
        }

        $interview->delete();

        return $this->success(null, 'Interview experience deleted successfully');
    }

    // POST /api/interviews/{interview}/publish
    public function publish(Interview $interview): JsonResponse
    {
        if ($interview->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if ($interview->status === 'published') {
            return $this->error('Interview is already published', 409);
        }

        $interview->publish();
        $interview->company->incrementInterviewCount();

        return $this->success($interview, 'Interview experience published successfully');
    }

    // POST /api/interviews/{interview}/upvote
    public function upvote(Interview $interview): JsonResponse
    {
        $user = auth()->user();

        if ($interview->isUpvotedBy($user)) {
            $interview->upvotedBy()->detach($user->id);
            $interview->decrement('upvote_count');
            return $this->success(null, 'Upvote removed');
        }

        $interview->upvotedBy()->attach($user->id);
        $interview->increment('upvote_count');

        return $this->success(null, 'Interview upvoted');
    }

    // POST /api/interviews/{interview}/bookmark
    public function bookmark(Interview $interview): JsonResponse
    {
        $user = auth()->user();

        if ($interview->isBookmarkedBy($user)) {
            $interview->bookmarkedBy()->detach($user->id);
            $interview->decrement('bookmark_count');
            return $this->success(null, 'Bookmark removed');
        }

        $interview->bookmarkedBy()->attach($user->id);
        $interview->increment('bookmark_count');

        return $this->success(null, 'Interview bookmarked');
    }
}