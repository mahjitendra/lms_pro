<?php

namespace App\Http\Controllers\API\V1\AI\DeepLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\AI\DeepLearning\NeuralNetworkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NeuralNetworkController extends Controller
{
    protected $neuralNetworkService;

    public function __construct(NeuralNetworkService $neuralNetworkService)
    {
        $this->neuralNetworkService = $neuralNetworkService;
    }

    /**
     * Display a listing of the available neural network architectures.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $architectures = $this->neuralNetworkService->getAvailableArchitectures();
            return ResponseHelper::success($architectures, 'Available architectures retrieved successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Could not retrieve neural network architectures: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified neural network architecture.
     *
     * @param string $architectureId
     * @return JsonResponse
     */
    public function show(string $architectureId): JsonResponse
    {
        try {
            $architecture = $this->neuralNetworkService->getArchitectureDetails($architectureId);
            return ResponseHelper::success($architecture, 'Architecture details retrieved successfully.');
        } catch (\App\Exceptions\AI\ModelNotFoundException $e) {
            return ResponseHelper::notFound($e->getMessage());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Could not retrieve architecture details: ' . $e->getMessage());
        }
    }
}