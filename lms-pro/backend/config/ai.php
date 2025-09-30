<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration for all of the AI services
    | used by the application. This includes internal services like our
    | Python ML microservice and external services like OpenAI.
    |
    */

    'services' => [

        'python_ml' => [
            'url' => env('PYTHON_ML_SERVICE_URL', 'http://localhost:5000'),
            'secret' => env('PYTHON_ML_SERVICE_SECRET'),
        ],

        'openai' => [
            'key' => env('OPENAI_API_KEY'),
            'organization' => env('OPENAI_ORGANIZATION'),
        ],

        // Add other services here, e.g., 'aws_rekognition', 'google_vision'
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Models
    |--------------------------------------------------------------------------
    |
    | Specify the default model IDs to be used for various AI tasks. This
    | allows for easily swapping out models without changing code.
    |
    */

    'defaults' => [
        'recommendation_model' => env('AI_DEFAULT_RECOMMENDATION_MODEL_ID', 1),
        'classification_model' => env('AI_DEFAULT_CLASSIFICATION_MODEL_ID', 2),
    ],

];