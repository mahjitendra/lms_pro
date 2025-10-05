<?php

namespace App\Services\AI\DeepLearning;

use App\Jobs\AI\TrainModelJob;
use App\Models\AI\AIModel;
use App\Models\AI\Dataset;
use App\Models\AI\TrainingJob;
use App\Repositories\Contracts\AIModelRepositoryInterface;
use App\Repositories\Contracts\DatasetRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModelTrainingService
{
    protected $aiModelRepository;
    protected $datasetRepository;

    public function __construct(
        AIModelRepositoryInterface $aiModelRepository,
        DatasetRepositoryInterface $datasetRepository
    ) {
        $this->aiModelRepository = $aiModelRepository;
        $this->datasetRepository = $datasetRepository;
    }

    /**
     * Start a new model training job.
     *
     * @param int $modelId
     * @param int $datasetId
     * @param array $hyperparameters
     * @return TrainingJob
     * @throws ModelNotFoundException
     */
    public function startTrainingJob(int $modelId, int $datasetId, array $hyperparameters = []): TrainingJob
    {
        // 1. Retrieve the model and dataset. Throws ModelNotFoundException if not found.
        $model = $this->aiModelRepository->findById($modelId);
        if (!$model) {
            throw new ModelNotFoundException("AI Model with ID {$modelId} not found.");
        }

        $dataset = $this->datasetRepository->findById($datasetId);
        if (!$dataset) {
            throw new ModelNotFoundException("Dataset with ID {$datasetId} not found.");
        }

        // 2. Create a TrainingJob record to track the process.
        $trainingJob = TrainingJob::create([
            'model_id' => $model->id,
            'dataset_id' => $dataset->id,
            'status' => 'queued',
            'hyperparameters' => $hyperparameters,
        ]);

        // 3. Dispatch the job to the queue.
        // We can associate the job with our tracking model.
        TrainModelJob::dispatch($model, $dataset, $trainingJob)->onQueue('training');

        return $trainingJob;
    }
}