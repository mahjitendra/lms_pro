<?php

namespace App\Repositories\Course;

use App\Models\Course\Course;
use App\Models\Course\Enrollment;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of the EnrollmentRepositoryInterface.
 */
class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    /**
     * @var Enrollment
     */
    protected $model;

    /**
     * EnrollmentRepository constructor.
     *
     * @param Enrollment $model
     */
    public function __construct(Enrollment $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Enrollment
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(User $user, Course $course): Enrollment
    {
        return $this->model->firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ], [
            'progress' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByUserAndCourse(User $user, Course $course): ?Enrollment
    {
        return $this->model->where('user_id', $user->id)
                           ->where('course_id', $course->id)
                           ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getEnrolledCoursesForUser(User $user): Collection
    {
        return $user->enrolledCourses()->with('course.instructor')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function updateProgress(int $enrollmentId, float $progress): bool
    {
        $enrollment = $this->findById($enrollmentId);
        if ($enrollment) {
            $enrollment->progress = min(100, max(0, $progress)); // Clamp progress between 0 and 100
            return $enrollment->save();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Enrollment $enrollment): bool
    {
        return $enrollment->delete();
    }
}