<?php

namespace App\Repositories\AI;

use App\Models\AI\AIModel;
use App\Repositories\Contracts\AIModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Eloquent implementation of the AIModelRepositoryInterface.
 *
 * This class provides a concrete implementation of the AI model repository
 * using Laravel's Eloquent ORM.
 */
class AIModelRepository implements AIModelRepositoryInterface
{
    /**
     * @var AIModel
     */
    protected $model;

    /**
     * AIModelRepository constructor.
     *
     * @param AIModel $model
     */
    public function __construct(AIModel $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?AIModel
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): AIModel
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): bool
    {
        $model = $this->findById($id);
        if ($model) {
            return $model->update($data);
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $model = $this->findById($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function findByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }
}