<?php

namespace App\Repositories\Contracts;

use App\Models\Course\Course;
use App\Models\Course\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for Enrollment data access.
 */
interface EnrollmentRepositoryInterface
{
    /**
     * Find a specific enrollment record by its ID.
     *
     * @param int $id
     * @return Enrollment|null
     */
    public function findById(int $id): ?Enrollment;

    /**
     * Create an enrollment record for a user in a course.
     *
     * @param User $user
     * @param Course $course
     * @return Enrollment
     */
    public function create(User $user, Course $course): Enrollment;

    /**
     * Find an enrollment for a specific user and course.
     *
     * @param User $user
     * @param Course $course
     * @return Enrollment|null
     */
    public function findByUserAndCourse(User $user, Course $course): ?Enrollment;

    /**
     * Get all courses a user is enrolled in.
     *
     * @param User $user
     * @return Collection
     */
    public function getEnrolledCoursesForUser(User $user): Collection;

    /**
     * Update the progress of an enrollment.
     *
     * @param int $enrollmentId
     * @param float $progress
     * @return bool
     */
    public function updateProgress(int $enrollmentId, float $progress): bool;

    /**
     * Unenroll a user from a course.
     *
     * @param Enrollment $enrollment
     * @return bool
     */
    public function delete(Enrollment $enrollment): bool;
}