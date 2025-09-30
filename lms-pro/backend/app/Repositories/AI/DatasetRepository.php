<?php

namespace App\Repositories\AI;

use App\Models\AI\Dataset;
use App\Repositories\Contracts\DatasetRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Eloquent implementation of the DatasetRepositoryInterface.
 */
class DatasetRepository implements DatasetRepositoryInterface
{
    /**
     * @var Dataset
     */
    protected $model;

    /**
     * DatasetRepository constructor.
     *
     * @param Dataset $model
     */
    public function __construct(Dataset $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Dataset
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function allPaginated(int $perPage = 20)
    {
        return $this->model->latest()->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Dataset
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): bool
    {
        $dataset = $this->findById($id);
        if ($dataset) {
            return $dataset->update($data);
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $dataset = $this->findById($id);

        if (!$dataset) {
            return false;
        }

        // Attempt to delete the associated file from storage
        if ($dataset->file_path && Storage::disk('local')->exists($dataset->file_path)) {
            try {
                Storage::disk('local')->delete($dataset->file_path);
            } catch (\Exception $e) {
                // Log the error but proceed with deleting the DB record
                Log::error("Failed to delete dataset file from storage for dataset ID: {$id}", [
                    'file_path' => $dataset->file_path,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Delete the database record
        return $dataset->delete();
    }
}