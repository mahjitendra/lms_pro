<?php

namespace App\Http\Controllers\API\V1\AI\NLP;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\QuestionAnsweringRequest; // To be created
use App\Services\AI\NLP\QuestionAnsweringService;
use Illuminate\Http\JsonResponse;

class QuestionAnsweringController extends Controller
{
    protected $qaService;

    public function __construct(QuestionAnsweringService $qaService)
    {
        $this->qaService = $qaService;
    }

    /**
     * Find an answer to a question within a given context.
     *
     * @param QuestionAnsweringRequest $request
     * @return JsonResponse
     */
    public function findAnswer(QuestionAnsweringRequest $request): JsonResponse
    {
        try {
            $question = $request->input('question');
            $context = $request->input('context');

            $result = $this->qaService->findAnswerInContext($question, $context);

            return ResponseHelper::success($result, 'Answer found successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred while finding the answer: ' . $e->getMessage());
        }
    }
}