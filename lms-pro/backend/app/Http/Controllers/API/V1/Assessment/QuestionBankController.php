<?php

namespace App\Http\Controllers\API\V1\Assessment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreQuestionRequest; // To be created
use App\Models\Assessment\Question;
use App\Services\Assessment\QuestionBankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    protected $questionBankService;

    public function __construct(QuestionBankService $questionBankService)
    {
        $this->questionBankService = $questionBankService;
    }

    /**
     * Display a listing of the questions in the bank.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Allow filtering by category or other criteria
        $filters = $request->only(['category', 'type']);
        $questions = $this->questionBankService->getAllQuestions($filters);
        return ResponseHelper::success($questions);
    }

    /**
     * Store a newly created question in the bank.
     *
     * @param StoreQuestionRequest $request
     * @return JsonResponse
     */
    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $question = $this->questionBankService->createQuestion($request->validated());
        return ResponseHelper::success($question, 'Question added to the bank successfully.', 201);
    }

    /**
     * Display the specified question.
     *
     * @param Question $question
     * @return JsonResponse
     */
    public function show(Question $question): JsonResponse
    {
        return ResponseHelper::success($question);
    }

    /**
     * Update the specified question in the bank.
     *
     * @param StoreQuestionRequest $request
     * @param Question $question
     * @return JsonResponse
     */
    public function update(StoreQuestionRequest $request, Question $question): JsonResponse
    {
        $updatedQuestion = $this->questionBankService->updateQuestion($question, $request->validated());
        return ResponseHelper::success($updatedQuestion, 'Question updated successfully.');
    }

    /**
     * Remove the specified question from the bank.
     *
     * @param Question $question
     * @return JsonResponse
     */
    public function destroy(Question $question): JsonResponse
    {
        $this->questionBankService->deleteQuestion($question);
        return ResponseHelper::success(null, 'Question removed from the bank successfully.', 204);
    }
}