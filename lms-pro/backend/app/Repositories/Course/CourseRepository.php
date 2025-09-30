<?php

namespace App\Repositories\Course;

use App\Models\Course\Course;
use App\Models\User;
use App\Models\Course\Enrollment;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Eloquent implementation of the CourseRepositoryInterface.
 *
 * This class provides a concrete implementation of the course repository
 * using Laravel's Eloquent ORM.
 */
class CourseRepository implements CourseRepositoryInterface
{
    /**
     * @var Course
     */
    protected $model;

    /**
     * CourseRepository constructor.
     *
     * @param Course $model
     */
    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Course
    {
        return $this->model->with(['modules.lessons'])->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function allPaginated(int $perPage = 15)
    {
        return $this->model->with('instructor')->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Course
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): bool
    {
        $course = $this->findById($id);
        if ($course) {
            return $course->update($data);
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $course = $this->findById($id);
        if ($course) {
            return $course->delete();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function findByInstructor(int $instructorId): Collection
    {
        return $this->model->where('instructor_id', $instructorId)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function enroll(User $user, Course $course): Enrollment
    {
        // Use firstOrCreate to prevent duplicate enrollments
        return Enrollment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }
}