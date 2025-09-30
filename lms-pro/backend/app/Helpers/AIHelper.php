<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

/**
 * Helper class for AI-related utility functions.
 */
class AIHelper
{
    /**
     * Get the base URL for the Python ML service from the config.
     *
     * @return string
     */
    public static function getMLServiceUrl(): string
    {
        $url = Config::get('ai.services.python_ml.url');

        if (!$url) {
            throw new \RuntimeException('Python ML service URL is not configured in config/ai.php');
        }

        return rtrim($url, '/');
    }

    /**
     * Get a specific endpoint URL for the Python ML service.
     *
     * @param string $endpoint e.g., 'predict', 'train'
     * @return string
     */
    public static function getMLServiceEndpoint(string $endpoint): string
    {
        return self::getMLServiceUrl() . '/' . ltrim($endpoint, '/');
    }

    /**
     * Prepare data for a prediction request.
     *
     * This is a placeholder for more complex logic that might be needed
     * to format data correctly for different model types.
     *
     * @param mixed $inputData
     * @param \App\Models\AI\AIModel $model
     * @return array
     */
    public static function formatInputForModel($inputData, \App\Models\AI\AIModel $model): array
    {
        // Example: maybe some models expect the data to be nested
        // under a specific key.
        // if ($model->type === 'image_classification') {
        //     return ['image_b64' => base64_encode($inputData)];
        // }

        return ['input' => $inputData];
    }
}