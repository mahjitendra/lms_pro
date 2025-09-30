<?php

namespace App\Jobs\AI;

use App\Events\AI\PredictionGenerated;
use App\Exceptions\AI\PredictionException;
use App\Models\AI\AIModel;
use App\Models\AI\Dataset;
use App\Models\AI\Prediction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneratePredictionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $model;
    public $dataset;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\AI\AIModel $model
     * @param \App\Models\AI\Dataset $dataset
     */
    public function __construct(AIModel $model, Dataset $dataset)
    {
        $this->model = $model;
        $this->dataset = $dataset;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting GeneratePredictionsJob for Model ID: {$this->model->id} with Dataset ID: {$this->dataset->id}");

        // In a real scenario, you'd read the dataset file and iterate through its rows.
        // For this example, we'll simulate processing a few records.
        $simulatedData = [
            ['feature1' => 1, 'feature2' => 2],
            ['feature1' => 3, 'feature2' => 4],
            ['feature1' => 5, 'feature2' => 6],
        ];

        $mlServiceUrl = config('ai.services.python_ml.url');

        foreach ($simulatedData as $inputData) {
            try {
                $response = Http::timeout(30)->post("{$mlServiceUrl}/predict", [
                    'model_id' => $this->model->id,
                    'input_data' => $inputData,
                ]);

                if ($response->failed()) {
                    throw new PredictionException("ML service connection failed. Status: " . $response->status());
                }

                $responseData = $response->json();

                if ($responseData['status'] === 'success') {
                    // Create a record for the prediction
                    $prediction = Prediction::create([
                        'model_id' => $this->model->id,
                        'user_id' => null, // This is a system-generated prediction
                        'input' => $inputData,
                        'output' => $responseData['data']['prediction'],
                    ]);

                    // Dispatch an event for each prediction
                    PredictionGenerated::dispatch($prediction);
                } else {
                    throw new PredictionException("ML service returned an error: " . $responseData['message']);
                }
            } catch (\Exception $e) {
                Log::error("Error processing a prediction within GeneratePredictionsJob.", [
                    'model_id' => $this->model->id,
                    'input' => $inputData,
                    'error' => $e->getMessage(),
                ]);
                // Continue to the next item in the dataset
                continue;
            }
        }

        Log::info("GeneratePredictionsJob finished for Model ID: {$this->model->id}");
    }
}