<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// V1 Routes
Route::prefix('v1')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [App\Http\Controllers\API\V1\Auth\LoginController::class, 'login']);
        Route::post('register', [App\Http\Controllers\API\V1\Auth\RegisterController::class, 'register']);
        Route::post('logout', [App\Http\Controllers\API\V1\Auth\LogoutController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('forgot-password', [App\Http\Controllers\API\V1\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('reset-password', [App\Http\Controllers\API\V1\Auth\ResetPasswordController::class, 'reset']);
    });

    // Course routes
    Route::apiResource('courses', App\Http\Controllers\API\V1\Course\CourseController::class);
    Route::apiResource('courses.modules', App\Http\Controllers\API\V1\Course\ModuleController::class);
    Route::apiResource('courses.modules.lessons', App\Http\Controllers\API\V1\Course\LessonController::class);
    Route::apiResource('courses.enrollments', App\Http\Controllers\API\V1\Course\EnrollmentController::class);
    // Add other course related routes
});