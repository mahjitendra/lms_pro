<?php

namespace App\Http\Controllers\API\V1\AI\NLP;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\TextAnalysisRequest; // To be created
use App\Services\AI\NLP\TextAnalysisService;
use Illuminate\Http\JsonResponse;

class TextAnalysisController extends Controller
{
    protected $textAnalysisService;

    public function __construct(TextAnalysisService $textAnalysisService)
    {
        $this->textAnalysisService = $textAnalysisService;
    }

    /**
     * Perform foundational text analysis on a given text.
     *
     * @param TextAnalysisRequest $request
     * @return JsonResponse
     */
    public function analyze(TextAnalysisRequest $request): JsonResponse
    {
        try {
            $text = $request->input('text');
            $features = $request->input('features', ['tokens', 'pos_tags', 'entities']);

            $result = $this->textAnalysisService->analyze($text, $features);

            return ResponseHelper::success($result, 'Text analysis performed successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during text analysis: ' . $e->getMessage());
        }
    }
}