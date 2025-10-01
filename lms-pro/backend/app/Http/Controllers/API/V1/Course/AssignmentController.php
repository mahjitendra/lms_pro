<?php

namespace App\Http\Controllers\API\V1\Course;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreAssignmentRequest; // To be created
use App\Models\Course\Assignment;
use App\Models\Course\Course;
use App\Services\Course\AssignmentService;
use Illuminate\Http\JsonResponse;

class AssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
        // Apply middleware for authorization
        // $this->authorizeResource(Assignment::class, 'assignment');
    }

    /**
     * Display a listing of the assignments for a specific course.
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function index(Course $course): JsonResponse
    {
        $assignments = $this->assignmentService->getAssignmentsForCourse($course);
        return ResponseHelper::success($assignments);
    }

    /**
     * Store a newly created assignment in storage.
     *
     * @param StoreAssignmentRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function store(StoreAssignmentRequest $request, Course $course): JsonResponse
    {
        $assignment = $this->assignmentService->createAssignment($course, $request->validated());
        return ResponseHelper::success($assignment, 'Assignment created successfully.', 201);
    }

    /**
     * Display the specified assignment.
     *
     * @param Course $course
     * @param Assignment $assignment
     * @return JsonResponse
     */
    public function show(Course $course, Assignment $assignment): JsonResponse
    {
        // Ensure the assignment belongs to the course
        if ($assignment->course_id !== $course->id) {
            return ResponseHelper::notFound('Assignment not found in this course.');
        }
        return ResponseHelper::success($assignment->load('submissions'));
    }

    /**
     * Update the specified assignment in storage.
     *
     * @param StoreAssignmentRequest $request
     * @param Course $course
     * @param Assignment $assignment
     * @return JsonResponse
     */
    public function update(StoreAssignmentRequest $request, Course $course, Assignment $assignment): JsonResponse
    {
        if ($assignment->course_id !== $course->id) {
            return ResponseHelper::notFound('Assignment not found in this course.');
        }
        $updatedAssignment = $this->assignmentService->updateAssignment($assignment, $request->validated());
        return ResponseHelper::success($updatedAssignment, 'Assignment updated successfully.');
    }

    /**
     * Remove the specified assignment from storage.
     *
     * @param Course $course
     * @param Assignment $assignment
     * @return JsonResponse
     */
    public function destroy(Course $course, Assignment $assignment): JsonResponse
    {
        if ($assignment->course_id !== $course->id) {
            return ResponseHelper::notFound('Assignment not found in this course.');
        }
        $this->assignmentService->deleteAssignment($assignment);
        return ResponseHelper::success(null, 'Assignment deleted successfully.', 204);
    }
}