<?php

namespace App\Http\Controllers\API\V1\AI\MachineLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\SentimentAnalysisRequest; // To be created
use App\Services\AI\MachineLearning\SentimentAnalysisService;
use Illuminate\Http\JsonResponse;

class SentimentAnalysisController extends Controller
{
    protected $sentimentService;

    public function __construct(SentimentAnalysisService $sentimentService)
    {
        $this->sentimentService = $sentimentService;
    }

    /**
     * Analyze the sentiment of a given text.
     *
     * @param SentimentAnalysisRequest $request
     * @return JsonResponse
     */
    public function analyze(SentimentAnalysisRequest $request): JsonResponse
    {
        try {
            $text = $request->input('text');
            $result = $this->sentimentService->analyze($text);

            return ResponseHelper::success($result, 'Sentiment analyzed successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during sentiment analysis: ' . $e->getMessage());
        }
    }
}