<?php

namespace App\Jobs\AI;

use App\Exceptions\AI\PredictionException;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateRecommendationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting GenerateRecommendationsJob for User ID: {$this->user->id}");

        // 1. Gather user data (e.g., enrolled courses, completed lessons, ratings)
        $userData = [
            'enrolled_courses' => $this->user->enrollments()->pluck('course_id')->toArray(),
            'profile_interests' => $this->user->profile->interests ?? [], // Assuming a profile relationship
        ];

        // 2. Call the ML service to get recommendations
        $mlServiceUrl = config('ai.services.python_ml.url');

        $response = Http::timeout(60)->post("{$mlServiceUrl}/recommendations", [
            'user_id' => $this->user->id,
            'user_data' => $userData,
        ]);

        if ($response->failed()) {
            throw new PredictionException("Failed to get recommendations from ML service for User ID: {$this->user->id}. Status: " . $response->status());
        }

        $responseData = $response->json();

        if ($responseData['status'] === 'success') {
            $recommendedCourseIds = $responseData['data']['recommended_course_ids'];

            // 3. Store the recommendations in the cache with a long TTL (e.g., 24 hours)
            $cacheKey = "user_{$this->user->id}_recommendations";
            Cache::put($cacheKey, $recommendedCourseIds, now()->addDay());

            Log::info("Successfully generated and cached recommendations for User ID: {$this->user->id}");
        } else {
            throw new PredictionException("ML service returned an error for recommendations: " . $responseData['message']);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("GenerateRecommendationsJob failed for User ID: {$this->user->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}