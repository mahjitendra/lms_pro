<?php

namespace App\Repositories\Contracts;

use App\Models\AI\Dataset;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for Dataset data access.
 */
interface DatasetRepositoryInterface
{
    /**
     * Retrieve a dataset by its ID.
     *
     * @param int $id
     * @return Dataset|null
     */
    public function findById(int $id): ?Dataset;

    /**
     * Retrieve all datasets, with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allPaginated(int $perPage = 20);

    /**
     * Create a new dataset record.
     *
     * @param array $data Should include 'name', 'description', 'file_path', 'file_size'
     * @return Dataset
     */
    public function create(array $data): Dataset;

    /**
     * Update dataset metadata.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a dataset record and its associated file.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}