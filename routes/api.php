<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Interview\InterviewController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'Dispatch API is running']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('companies')->group(function () {
    Route::get('/', [CompanyController::class, 'index']);
    Route::get('/{company}', [CompanyController::class, 'show']);
});

Route::prefix('interviews')->group(function () {
    Route::get('/', [InterviewController::class, 'index']);
    Route::get('/{interview}', [InterviewController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome Admin']);
    });
});

Route::middleware(['auth:sanctum', 'role:candidate'])->prefix('candidate')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome candidate']);
    });
});

Route::middleware(['auth:sanctum', 'role:company'])->prefix('company')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome company']);
    });
});

Route::middleware('auth:sanctum')->prefix('companies')->group(function () {
    Route::post('/{company}/follow', [CompanyController::class, 'follow']);
    Route::delete('/{company}/follow', [CompanyController::class, 'unfollow']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('companies')->group(function () {
    Route::post('/', [CompanyController::class, 'store']);
    Route::put('/{company}', [CompanyController::class, 'update']);
    Route::delete('/{company}', [CompanyController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:candidate'])->prefix('interviews')->group(function () {
    Route::post('/', [InterviewController::class, 'store']);
    Route::put('/{interview}', [InterviewController::class, 'update']);
    Route::delete('/{interview}', [InterviewController::class, 'destroy']);
    Route::post('/{interview}/publish', [InterviewController::class, 'publish']);
    Route::post('/{interview}/upvote', [InterviewController::class, 'upvote']);
    Route::post('/{interview}/bookmark', [InterviewController::class, 'bookmark']);
});