<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Interview\InterviewController;
use App\Http\Controllers\Question\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| System Health
|--------------------------------------------------------------------------
*/
Route::get('/health', fn () => response()->json(['status' => 'Dispatch API is running']));

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->middleware('throttle:6,1');
    Route::post('/login', 'login')->middleware('throttle:6,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', 'me');
        Route::post('/logout', 'logout');
    });
});

/*
|--------------------------------------------------------------------------
| Companies
|--------------------------------------------------------------------------
*/
Route::prefix('companies')->controller(CompanyController::class)->group(function () {
    // Public routes
    Route::get('/', 'index');
    Route::get('/{company}', 'show');

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/{company}/follow', 'follow');
        Route::delete('/{company}/follow', 'unfollow');

        // Admin management
        Route::middleware('role:admin')->group(function () {
            Route::post('/', 'store');
            Route::put('/{company}', 'update');
            Route::delete('/{company}', 'destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Interviews
|--------------------------------------------------------------------------
*/
Route::prefix('interviews')->controller(InterviewController::class)->group(function () {
    // Public routes
    Route::get('/', 'index');
    Route::get('/{interview}', 'show');

    // Candidate management
    Route::middleware(['auth:sanctum', 'role:candidate'])->group(function () {
        Route::post('/', 'store');
        Route::put('/{interview}', 'update');
        Route::delete('/{interview}', 'destroy');
        Route::post('/{interview}/publish', 'publish');
        Route::post('/{interview}/upvote', 'upvote');
        Route::post('/{interview}/bookmark', 'bookmark');
    });
});

/*
|--------------------------------------------------------------------------
| Questions
|--------------------------------------------------------------------------
*/
Route::prefix('questions')->controller(QuestionController::class)->group(function () {
    // Public routes
    Route::get('/', 'index');
    Route::get('/{question}', 'show');

    // Authenticated interactions
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', 'store');
        Route::post('/{question}/upvote', 'upvote');
        Route::post('/{question}/bookmark', 'bookmark');

        // Admin approval
        Route::middleware('role:admin')->put('/{question}/approve', 'approve');
    });
});

/*
|--------------------------------------------------------------------------
| Dashboards
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/dashboard', fn () => response()->json(['message' => 'Welcome Admin']))->middleware('role:admin');
    Route::get('/candidate/dashboard', fn () => response()->json(['message' => 'Welcome Candidate']))->middleware('role:candidate');
    Route::get('/company/dashboard', fn () => response()->json(['message' => 'Welcome Company']))->middleware('role:company');
});