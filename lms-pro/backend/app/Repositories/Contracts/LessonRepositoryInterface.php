<?php

namespace App\Repositories\Contracts;

use App\Models\Course\Lesson;
use App\Models\Course\Module;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for Lesson data access.
 */
interface LessonRepositoryInterface
{
    /**
     * Retrieve a lesson by its ID.
     *
     * @param int $id
     * @return Lesson|null
     */
    public function findById(int $id): ?Lesson;

    /**
     * Create a new lesson within a module.
     *
     * @param Module $module
     * @param array $data
     * @return Lesson
     */
    public function create(Module $module, array $data): Lesson;

    /**
     * Update an existing lesson.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a lesson.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get all lessons belonging to a specific module.
     *
     * @param int $moduleId
     * @return Collection
     */
    public function findByModule(int $moduleId): Collection;
}