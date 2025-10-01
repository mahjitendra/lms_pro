<?php

namespace App\Http\Controllers\API\V1\Assessment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\SubmitAssignmentRequest; // To be created
use App\Http\Requests\Assessment\SubmitQuizRequest; // To be created
use App\Models\Assessment\Assignment;
use App\Models\Assessment\Quiz;
use App\Services\Assessment\GradingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GradingController extends Controller
{
    protected $gradingService;

    public function __construct(GradingService $gradingService)
    {
        $this->gradingService = $gradingService;
    }

    /**
     * Handle the submission of an assignment by a student.
     *
     * @param SubmitAssignmentRequest $request
     * @param Assignment $assignment
     * @return JsonResponse
     */
    public function submitAssignment(SubmitAssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        try {
            $submission = $this->gradingService->handleAssignmentSubmission(
                Auth::user(),
                $assignment,
                $request->file('submission_file'),
                $request->input('comments')
            );
            return ResponseHelper::success($submission, 'Assignment submitted successfully for grading.', 202);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to submit assignment: ' . $e->getMessage());
        }
    }

    /**
     * Handle the submission of a quiz by a student.
     *
     * @param SubmitQuizRequest $request
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function submitQuiz(SubmitQuizRequest $request, Quiz $quiz): JsonResponse
    {
        try {
            // The service will handle creating the attempt, grading it, and returning the result.
            $result = $this->gradingService->handleQuizSubmission(
                Auth::user(),
                $quiz,
                $request->input('answers')
            );
            return ResponseHelper::success($result, 'Quiz submitted and graded successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to submit quiz: ' . $e->getMessage());
        }
    }

    /**
     * Manually grade a specific submission (for instructors).
     *
     * @param Request $request // Should be a dedicated Form Request
     * @param int $submissionId
     * @return JsonResponse
     */
    public function manualGrade(Request $request, int $submissionId): JsonResponse
    {
        // $this->authorize('grade', Submission::class); // Authorization check
        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        try {
            $grade = $this->gradingService->gradeSubmission($submissionId, $validated['grade'], $validated['feedback']);
            return ResponseHelper::success($grade, 'Submission graded successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to grade submission: ' . $e->getMessage());
        }
    }
}