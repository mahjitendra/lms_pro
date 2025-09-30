<?php

namespace App\Repositories\Course;

use App\Models\Course\Lesson;
use App\Models\Course\Module;
use App\Repositories\Contracts\LessonRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of the LessonRepositoryInterface.
 */
class LessonRepository implements LessonRepositoryInterface
{
    /**
     * @var Lesson
     */
    protected $model;

    /**
     * LessonRepository constructor.
     *
     * @param Lesson $model
     */
    public function __construct(Lesson $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Lesson
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Module $module, array $data): Lesson
    {
        return $module->lessons()->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): bool
    {
        $lesson = $this->findById($id);
        if ($lesson) {
            return $lesson->update($data);
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $lesson = $this->findById($id);
        if ($lesson) {
            return $lesson->delete();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function findByModule(int $moduleId): Collection
    {
        return $this->model->where('module_id', $moduleId)->orderBy('order', 'asc')->get();
    }
}