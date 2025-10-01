<?php

namespace App\Http\Controllers\API\V1\AI\ComputerVision;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\OcrRequest; // To be created
use App\Services\AI\ComputerVision\OcrService;
use Illuminate\Http\JsonResponse;

class OCRController extends Controller
{
    protected $ocrService;

    public function __construct(OcrService $ocrService)
    {
        $this->ocrService = $ocrService;
    }

    /**
     * Analyze an image to extract text using OCR.
     *
     * @param OcrRequest $request
     * @return JsonResponse
     */
    public function analyze(OcrRequest $request): JsonResponse
    {
        try {
            $imageFile = $request->file('image');

            $result = $this->ocrService->processOcr($imageFile);

            return ResponseHelper::success($result, 'OCR analysis job dispatched successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during OCR processing: ' . $e->getMessage());
        }
    }
}