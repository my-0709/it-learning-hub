<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\LearningRecordController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\WeakPointController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // 認証不要
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login',    [AuthController::class, 'login']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('terms',      [TermController::class, 'index']);
    Route::get('terms/{term}', [TermController::class, 'show']);
    Route::get('quizzes/random', [QuizController::class, 'random']);

    // 認証必須
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',     [AuthController::class, 'me']);

        Route::post('favorites/{term}',   [FavoriteController::class, 'toggle']);
        Route::get('favorites',            [FavoriteController::class, 'index']);

        Route::post('quiz-sessions',                   [QuizController::class, 'startSession']);
        Route::post('quiz-sessions/{quizSession}/answer', [QuizController::class, 'answer']);
        Route::post('quiz-sessions/{quizSession}/end',    [QuizController::class, 'endSession']);

        Route::get('learning-records',       [LearningRecordController::class, 'index']);
        Route::get('learning-records/stats', [LearningRecordController::class, 'stats']);

        Route::get('weak-points', [WeakPointController::class, 'index']);

        Route::put('user/profile',     [UserProfileController::class, 'update']);
        Route::post('user/avatar',     [UserProfileController::class, 'updateAvatar']);
        Route::put('user/password',    [UserProfileController::class, 'changePassword']);
        Route::delete('user/history',  [UserProfileController::class, 'resetHistory']);
        Route::delete('user',          [UserProfileController::class, 'destroy']);
    });
});
