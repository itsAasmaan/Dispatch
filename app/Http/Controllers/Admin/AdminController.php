<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Interview;
use App\Models\Question;
use App\Models\SalaryInsight;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // GET /api/admin/stats
    public function stats(): JsonResponse
    {
        return $this->success([
            'users' => [
                'total' => User::count(),
                'candidates' => User::role('candidate')->count(),
                'companies' => User::role('company')->count(),
                'new_today' => User::whereDate('created_at', today())->count(),
            ],
            'content' => [
                'interviews' => Interview::count(),
                'published' => Interview::where('status', 'published')->count(),
                'questions' => Question::count(),
                'approved' => Question::where('is_approved', true)->count(),
                'comments' => Comment::count(),
                'flagged' => Comment::where('is_flagged', true)->count(),
            ],
            'companies' => [
                'total' => Company::count(),
                'verified' => Company::where('is_verified', true)->count(),
            ],
            'salary_insights' => SalaryInsight::count(),
        ]);
    }

    // GET /api/admin/users
    public function users(Request $request): JsonResponse
    {
        $users = User::with('roles')
            ->when($request->role, fn($q) => $q->role($request->role))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return $this->success($users);
    }

    // POST /api/admin/users/{user}/toggle-active
    public function toggleUserActive(User $user): JsonResponse
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return $this->success(null, "User {$status} successfully");
    }

    // GET /api/admin/flagged-comments
    public function flaggedComments(): JsonResponse
    {
        $comments = Comment::where('is_flagged', true)
            ->with([
                'user:id,name,username',
                'commentable',
            ])
            ->latest()
            ->paginate(20);

        return $this->success($comments);
    }

    // POST /api/admin/comments/{comment}/dismiss-flag
    public function dismissFlag(Comment $comment): JsonResponse
    {
        $comment->update(['is_flagged' => false]);

        return $this->success(null, 'Flag dismissed');
    }

    // DELETE /api/admin/comments/{comment}
    public function deleteComment(Comment $comment): JsonResponse
    {
        $comment->delete();

        return $this->success(null, 'Comment deleted by admin');
    }

    // POST /api/admin/companies/{company}/verify
    public function verifyCompany(Company $company): JsonResponse
    {
        $company->update(['is_verified' => true]);

        return $this->success(null, "{$company->name} has been verified");
    }

    // GET /api/admin/pending-questions
    public function pendingQuestions(): JsonResponse
    {
        $questions = Question::where('is_approved', false)
            ->with('user:id,name,username')
            ->latest()
            ->paginate(20);

        return $this->success($questions);
    }
}