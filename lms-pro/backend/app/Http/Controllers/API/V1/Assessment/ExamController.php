<?php

namespace App\Http\Controllers\API\V1\Assessment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreExamRequest; // To be created
use App\Models\Assessment\Exam;
use App\Services\Assessment\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    /**
     * Display a listing of the exams.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $exams = $this->examService->getAllExams();
        return ResponseHelper::success($exams);
    }

    /**
     * Store a newly created exam in storage.
     *
     * @param StoreExamRequest $request
     * @return JsonResponse
     */
    public function store(StoreExamRequest $request): JsonResponse
    {
        $exam = $this->examService->createExam($request->validated());
        return ResponseHelper::success($exam, 'Exam created successfully.', 201);
    }

    /**
     * Display the specified exam with its questions.
     *
     * @param Exam $exam
     * @return JsonResponse
     */
    public function show(Exam $exam): JsonResponse
    {
        return ResponseHelper::success($exam->load('questions'));
    }

    /**
     * Update the specified exam in storage.
     *
     * @param StoreExamRequest $request
     * @param Exam $exam
     * @return JsonResponse
     */
    public function update(StoreExamRequest $request, Exam $exam): JsonResponse
    {
        $updatedExam = $this->examService->updateExam($exam, $request->validated());
        return ResponseHelper::success($updatedExam, 'Exam updated successfully.');
    }

    /**
     * Remove the specified exam from storage.
     *
     * @param Exam $exam
     * @return JsonResponse
     */
    public function destroy(Exam $exam): JsonResponse
    {
        $this->examService->deleteExam($exam);
        return ResponseHelper::success(null, 'Exam deleted successfully.', 204);
    }

    /**
     * Start an exam attempt for the authenticated user.
     *
     * @param Request $request
     * @param Exam $exam
     * @return JsonResponse
     */
    public function startAttempt(Request $request, Exam $exam): JsonResponse
    {
        try {
            $attempt = $this->examService->startAttempt($exam, $request->user());
            return ResponseHelper::success($attempt, 'Exam attempt started successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 409); // 409 Conflict if already started/completed
        }
    }
}