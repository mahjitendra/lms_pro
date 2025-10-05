<?php

namespace App\Services\AI\MachineLearning;

use App\Jobs\AI\GenerateRecommendationsJob;
use App\Models\Course\Course;
use App\Models\User;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get course recommendations for a specific user.
     *
     * @param User $user
     * @param int $limit The number of recommendations to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForUser(User $user, int $limit = 10)
    {
        $cacheKey = "user_{$user->id}_recommendations";

        // 1. Try to get the recommended course IDs from the cache first.
        $recommendedIds = Cache::get($cacheKey);

        if (is_null($recommendedIds)) {
            // 2. If the cache is empty, dispatch a job to generate them.
            Log::info("Recommendation cache miss for User ID: {$user->id}. Dispatching generation job.");
            GenerateRecommendationsJob::dispatch($user)->onQueue('recommendations');

            // Return a default set of popular courses for now, so the user doesn't see an empty list.
            // In a real app, you might have a pre-computed list of generic recommendations.
            return Course::where('is_popular', true)->take($limit)->get();
        }

        Log::info("Recommendation cache hit for User ID: {$user->id}.");

        // 3. If we have IDs from the cache, fetch the corresponding course models.
        if (empty($recommendedIds)) {
            return collect(); // Return an empty collection if no recommendations were found.
        }

        return Course::whereIn('id', $recommendedIds)
            ->take($limit)
            ->get();
    }
}