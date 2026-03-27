<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\WishlistController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

Route::middleware('auth:api')->group(function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:teacher')->group(function (): void {
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{course}', [CourseController::class, 'update']);
        Route::delete('/courses/{course}', [CourseController::class, 'destroy']);

        Route::get('/teacher/courses/{courseId}/students', [TeacherController::class, 'students']);
        Route::get('/teacher/stats', [TeacherController::class, 'stats']);
        Route::get('/teacher/groups', [TeacherController::class, 'groups']);
    });

    Route::middleware('role:student')->group(function (): void {
        Route::get('/recommendations', [RecommendationController::class, 'index']);

        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist/{course}', [WishlistController::class, 'store']);
        Route::delete('/wishlist/{course}', [WishlistController::class, 'destroy']);

        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store']);
        Route::delete('/courses/{course}/enroll', [EnrollmentController::class, 'destroy']);
    });
});
