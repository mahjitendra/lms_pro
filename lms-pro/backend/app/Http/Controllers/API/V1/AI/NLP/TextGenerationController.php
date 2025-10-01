<?php

namespace App\Http\Controllers\API\V1\AI\NLP;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\TextGenerationRequest; // To be created
use App\Services\AI\NLP\TextGenerationService;
use Illuminate\Http\JsonResponse;

class TextGenerationController extends Controller
{
    protected $textGenerationService;

    public function __construct(TextGenerationService $textGenerationService)
    {
        $this->textGenerationService = $textGenerationService;
    }

    /**
     * Generate text based on a given prompt.
     *
     * @param TextGenerationRequest $request
     * @return JsonResponse
     */
    public function generate(TextGenerationRequest $request): JsonResponse
    {
        try {
            $prompt = $request->input('prompt');
            $maxLength = $request->input('max_length', 150);

            $result = $this->textGenerationService->generateText($prompt, $maxLength);

            return ResponseHelper::success($result, 'Text generated successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during text generation: ' . $e->getMessage());
        }
    }
}