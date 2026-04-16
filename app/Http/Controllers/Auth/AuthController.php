<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->created([
            'user' => $this->formatUser($user),
            'token' => $token,
        ], 'Registration Successful');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update(['last_login_at' => now()]);

        return $this->success([
            'user' => $this->formatUser($user),
            'token' => $token,
        ], 'Login Successful');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success([
            'user' => $this->formatUser($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    // -------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------
    private function formatUser(User $user): array
    {
        return [
            'id'                  => $user->id,
            'name'                => $user->name,
            'username'            => $user->username,
            'email'               => $user->email,
            'role'                => $user->getRoleNames()->first(),
            'avatar'              => $user->avatar,
            'bio'                 => $user->bio,
            'current_role'        => $user->current_role,
            'current_company'     => $user->current_company,
            'years_of_experience' => $user->years_of_experience,
            'github_url'          => $user->github_url,
            'linkedin_url'        => $user->linkedin_url,
            'portfolio_url'       => $user->portfolio_url,
            'last_login_at'       => $user->last_login_at,
        ];
    }
}
