<?php

namespace App\Repositories\AI;

use App\Models\AI\Prediction;
use App\Models\User;
use App\Repositories\Contracts\PredictionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of the PredictionRepositoryInterface.
 */
class PredictionRepository implements PredictionRepositoryInterface
{
    /**
     * @var Prediction
     */
    protected $model;

    /**
     * PredictionRepository constructor.
     *
     * @param Prediction $model
     */
    public function __construct(Prediction $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Prediction
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Prediction
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function findByUser(User $user, int $perPage = 15)
    {
        return $this->model->where('user_id', $user->id)
                           ->with('model')
                           ->latest()
                           ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function findByModel(int $modelId): Collection
    {
        return $this->model->where('model_id', $modelId)->get();
    }
}