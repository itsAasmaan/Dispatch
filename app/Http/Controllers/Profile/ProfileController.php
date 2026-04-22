<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    // GET /api/profile/{username}
    public function show(string $username): JsonResponse
    {
        $user = User::where('username', $username)->where('is_active', true)->firstOrFail();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'bio' => $user->bio,
            'avatar' => $user->avatar,
            'current_role' => $user->current_role,
            'current_company' => $user->current_company,
            'years_of_experience' => $user->years_of_experience,
            'github_url' => $user->github_url,
            'linkedin_url' => $user->linkedin_url,
            'portfolio_url' => $user->portfolio_url,
            'role' => $user->getRoleNames()->first(),
            'stats' => [
                'interviews_shared' => Interview::where('user_id', $user->id)->where('status', 'published')->count(),
                'member_since' => $user->created_at->format('M Y'),
            ],
        ];

        return $this->success($data);
    }

    // PUT /api/profile
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        $user->update($request->validated());

        return $this->success($user, 'Profile updated successfully');
    }

    // GET /api/profile/my-interviews
    public function myInterviews(): JsonResponse
    {
        $interviews = Interview::where('user_id', auth()->id())
            ->with('company:id,name,slug,logo')
            ->latest()
            ->paginate(10);

        return $this->success($interviews);
    }

    // GET /api/profile/my-bookmarks
    public function myBookmarks(): JsonResponse
    {
        $user = auth()->user();

        $bookmarks = $user->load([
            'bookmarkedInterviews' => fn($q) => $q
                ->with('company:id,name,slug,logo')
                ->latest()
                ->paginate(10),
        ]);

        return $this->success($bookmarks->bookmarkedInterviews ?? []);
    }
}