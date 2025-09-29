<?php

namespace App\Console\Commands\AI;

use App\Jobs\AI\TrainModelJob;
use App\Models\AI\AIModel;
use App\Models\AI\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TrainModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:train-model {model_id} {dataset_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job to train a specific AI model with a given dataset';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelId = $this->argument('model_id');
        $datasetId = $this->argument('dataset_id');

        $this->info("Attempting to dispatch training job for Model ID: {$modelId} with Dataset ID: {$datasetId}...");

        try {
            // Find the model and dataset from the database
            $model = AIModel::findOrFail($modelId);
            $dataset = Dataset::findOrFail($datasetId);

            // Dispatch the job to the queue
            TrainModelJob::dispatch($model, $dataset);

            $this->info("Successfully dispatched training job for model: {$model->name}.");
            Log::info("Dispatched training job for AIModel ID: {$model->id}");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->error("Error: Model or Dataset not found. Please check the provided IDs.");
            Log::error("TrainModelCommand: Model or Dataset not found.", ['model_id' => $modelId, 'dataset_id' => $datasetId, 'error' => $e->getMessage()]);
            return 1; // Return a non-zero status code for failure
        } catch (\Exception $e) {
            $this->error("An unexpected error occurred: " . $e->getMessage());
            Log::error("TrainModelCommand: Failed to dispatch job.", ['model_id' => $modelId, 'dataset_id' => $datasetId, 'error' => $e->getMessage()]);
            return 1;
        }

        return 0; // Success
    }
}