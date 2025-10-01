<?php

namespace App\Http\Controllers\API\V1\Course;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreQuizRequest; // To be created
use App\Models\Course\Course;
use App\Models\Course\Quiz;
use App\Services\Course\QuizService;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
        // Apply middleware for authorization
        // $this->authorizeResource(Quiz::class, 'quiz');
    }

    /**
     * Display a listing of the quizzes for a specific course.
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function index(Course $course): JsonResponse
    {
        $quizzes = $this->quizService->getQuizzesForCourse($course);
        return ResponseHelper::success($quizzes);
    }

    /**
     * Store a newly created quiz in storage.
     *
     * @param StoreQuizRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function store(StoreQuizRequest $request, Course $course): JsonResponse
    {
        $quiz = $this->quizService->createQuiz($course, $request->validated());
        return ResponseHelper::success($quiz, 'Quiz created successfully.', 201);
    }

    /**
     * Display the specified quiz with its questions.
     *
     * @param Course $course
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function show(Course $course, Quiz $quiz): JsonResponse
    {
        if ($quiz->course_id !== $course->id) {
            return ResponseHelper::notFound('Quiz not found in this course.');
        }
        return ResponseHelper::success($quiz->load('questions'));
    }

    /**
     * Update the specified quiz in storage.
     *
     * @param StoreQuizRequest $request
     * @param Course $course
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function update(StoreQuizRequest $request, Course $course, Quiz $quiz): JsonResponse
    {
        if ($quiz->course_id !== $course->id) {
            return ResponseHelper::notFound('Quiz not found in this course.');
        }
        $updatedQuiz = $this->quizService->updateQuiz($quiz, $request->validated());
        return ResponseHelper::success($updatedQuiz, 'Quiz updated successfully.');
    }

    /**
     * Remove the specified quiz from storage.
     *
     * @param Course $course
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function destroy(Course $course, Quiz $quiz): JsonResponse
    {
        if ($quiz->course_id !== $course->id) {
            return ResponseHelper::notFound('Quiz not found in this course.');
        }
        $this->quizService->deleteQuiz($quiz);
        return ResponseHelper::success(null, 'Quiz deleted successfully.', 204);
    }
}