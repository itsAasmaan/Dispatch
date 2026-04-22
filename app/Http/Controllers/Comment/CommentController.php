<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Interview;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    // GET /api/interviews/{interview}/comments
    public function interviewComments(Interview $interview): JsonResponse
    {
        $comments = $interview->comments()
            ->withCount('replies')
            ->paginate(20);

        return $this->success($comments);
    }

    // GET /api/questions/{question}/comments
    public function questionComments(Question $question): JsonResponse
    {
        $comments = $question->comments()
            ->withCount('replies')
            ->paginate(20);

        return $this->success($comments);
    }

    // POST /api/interviews/{interview}/comments
    public function storeForInterview(
        StoreCommentRequest $request,
        Interview $interview
    ): JsonResponse {
        $comment = $interview->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'parent_id' => $request->parent_id,
        ]);

        $interview->increment('comment_count');

        return $this->created(
            $comment->load('user:id,name,username,avatar'),
            'Comment posted'
        );
    }

    // POST /api/questions/{question}/comments
    public function storeForQuestion(
        StoreCommentRequest $request,
        Question $question
    ): JsonResponse {
        $comment = $question->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'parent_id' => $request->parent_id,
        ]);

        return $this->created(
            $comment->load('user:id,name,username,avatar'),
            'Comment posted'
        );
    }

    // DELETE /api/comments/{comment}
    public function destroy(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return $this->forbidden();
        }

        $comment->delete();

        return $this->success(null, 'Comment deleted');
    }

    // POST /api/comments/{comment}/upvote
    public function upvote(Comment $comment): JsonResponse
    {
        $user = auth()->user();

        if ($comment->isUpvotedBy($user)) {
            $comment->upvotedBy()->detach($user->id);
            $comment->decrement('upvote_count');
            return $this->success(null, 'Upvote removed');
        }

        $comment->upvotedBy()->attach($user->id);
        $comment->increment('upvote_count');

        return $this->success(null, 'Comment upvoted');
    }

    // POST /api/comments/{comment}/flag
    public function flag(Comment $comment): JsonResponse
    {
        $comment->update(['is_flagged' => true]);

        return $this->success(null, 'Comment flagged for review');
    }
}