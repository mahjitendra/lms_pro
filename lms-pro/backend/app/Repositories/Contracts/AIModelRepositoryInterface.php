<?php

namespace App\Repositories\Contracts;

use App\Models\AI\AIModel;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for AI Model data access.
 *
 * This contract defines the standard methods that any AI model repository
 * implementation must provide. It ensures a consistent API for interacting
 * with AI model data throughout the application.
 */
interface AIModelRepositoryInterface
{
    /**
     * Retrieve an AI model by its ID.
     *
     * @param int $id
     * @return AIModel|null
     */
    public function findById(int $id): ?AIModel;

    /**
     * Retrieve all AI models.
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Create a new AI model.
     *
     * @param array $data
     * @return AIModel
     */
    public function create(array $data): AIModel;

    /**
     * Update an existing AI model.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete an AI model by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Find models by a specific type.
     *
     * @param string $type
     * @return Collection
     */
    public function findByType(string $type): Collection;
}