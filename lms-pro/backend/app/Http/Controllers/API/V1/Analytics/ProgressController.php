<?php

namespace App\Http\Controllers\API\V1\Analytics;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\User;
use App\Services\Analytics\ProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Get the progress of the authenticated user for a specific course.
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function getMyCourseProgress(Course $course): JsonResponse
    {
        try {
            $progress = $this->progressService->getUserProgressForCourse(Auth::user(), $course);
            return ResponseHelper::success($progress);
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }

    /**
     * Get the progress of all students in a specific course (for instructors).
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function getStudentProgressForCourse(Course $course): JsonResponse
    {
        // $this->authorize('viewProgress', $course); // Authorization check

        try {
            $progressData = $this->progressService->getAllStudentProgressForCourse($course);
            return ResponseHelper::success($progressData);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Could not retrieve student progress: ' . $e->getMessage());
        }
    }

    /**
     * Update a user's progress for a specific lesson (e.g., when they mark it as complete).
     *
     * @param Request $request // Should be a dedicated Form Request
     * @param int $lessonId
     * @return JsonResponse
     */
    public function updateLessonProgress(Request $request, int $lessonId): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:completed,in_progress',
        ]);

        try {
            $this->progressService->updateLessonProgress(Auth::user(), $lessonId, $validated['status']);
            return ResponseHelper::success(null, 'Progress updated successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to update progress: ' . $e->getMessage());
        }
    }
}