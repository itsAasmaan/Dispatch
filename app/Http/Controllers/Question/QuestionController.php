<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // GET /api/questions
    public function index(Request $request): JsonResponse
    {
        $questions = Question::approved()
            ->when($request->category, fn($q) => $q->byCategory($request->category))
            ->when($request->difficulty, fn($q) => $q->byDifficulty($request->difficulty))
            ->when($request->company, fn($q) => $q->byCompany($request->company))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->tag, fn($q) => $q->whereJsonContains('tags', $request->tag))
            ->orderBy('upvote_count', 'desc')
            ->paginate(20);

        return $this->success($questions);
    }

    // GET /api/questions/{question}
    public function show(Question $question): JsonResponse
    {
        if (!$question->is_approved && auth()->id() !== $question->user_id) {
            return $this->notFound();
        }

        $question->load('user:id,name,username,avatar');
        $question->increment('view_count');

        if (auth()->check()) {
            $question->is_upvoted = $question->isUpvotedBy(auth()->user());
            $question->is_bookmarked = $question->isBookmarkedBy(auth()->user());
        }

        return $this->success($question);
    }

    // POST /api/questions
    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $question = Question::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return $this->created($question, 'Question submitted for review');
    }

    // POST /api/questions/{question}/upvote
    public function upvote(Question $question): JsonResponse
    {
        $user = auth()->user();

        if ($question->isUpvotedBy($user)) {
            $question->upvotedBy()->detach($user->id);
            $question->decrement('upvote_count');
            return $this->success(null, 'Upvote removed');
        }

        $question->upvotedBy()->attach($user->id);
        $question->increment('upvote_count');

        return $this->success(null, 'Question upvoted');
    }

    // POST /api/questions/{question}/bookmark
    public function bookmark(Question $question): JsonResponse
    {
        $user = auth()->user();

        if ($question->isBookmarkedBy($user)) {
            $question->bookmarkedBy()->detach($user->id);
            $question->decrement('bookmark_count');
            return $this->success(null, 'Bookmark removed');
        }

        $question->bookmarkedBy()->attach($user->id);
        $question->increment('bookmark_count');

        return $this->success(null, 'Question bookmarked');
    }

    // PUT /api/admin/questions/{question}/approve  (admin only)
    public function approve(Question $question): JsonResponse
    {
        $question->update(['is_approved' => true]);

        return $this->success(null, 'Question approved successfully');
    }
}