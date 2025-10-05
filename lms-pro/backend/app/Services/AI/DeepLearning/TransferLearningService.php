<?php

namespace App\Services\AI\DeepLearning;

use App\Jobs\AI\TransferLearningJob; // Assuming this job will be created
use App\Models\AI\AIModel;
use App\Models\AI\Dataset;
use App\Repositories\Contracts\AIModelRepositoryInterface;
use App\Repositories\Contracts\DatasetRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransferLearningService
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
     * Start a new transfer learning job.
     *
     * @param int $baseModelId The ID of the pre-trained model to use as a base.
     * @param int $customDatasetId The ID of the dataset to fine-tune on.
     * @param string $newModelName The name for the new, fine-tuned model.
     * @return AIModel The record for the new model being trained.
     * @throws ModelNotFoundException
     */
    public function startFineTuningJob(int $baseModelId, int $customDatasetId, string $newModelName): AIModel
    {
        // 1. Retrieve the base model and dataset.
        $baseModel = $this->aiModelRepository->findById($baseModelId);
        if (!$baseModel) {
            throw new ModelNotFoundException("Base AI Model with ID {$baseModelId} not found.");
        }

        $customDataset = $this->datasetRepository->findById($customDatasetId);
        if (!$customDataset) {
            throw new ModelNotFoundException("Custom dataset with ID {$customDatasetId} not found.");
        }

        // 2. Create a new AIModel record for the model that will be fine-tuned.
        // It inherits properties from the base model but gets a new name and 'pending' status.
        $newModel = $this->aiModelRepository->create([
            'name' => $newModelName,
            'description' => "Fine-tuned version of '{$baseModel->name}' on dataset '{$customDataset->name}'.",
            'type' => $baseModel->type, // Inherits the type
            'base_model_id' => $baseModel->id,
            'status' => 'pending', // Will be updated by the training job
        ]);

        // 3. Dispatch the job to the queue.
        TransferLearningJob::dispatch($baseModel, $customDataset, $newModel)->onQueue('training');

        return $newModel;
    }
}