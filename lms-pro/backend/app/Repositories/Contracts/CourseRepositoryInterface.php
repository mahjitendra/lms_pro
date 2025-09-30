<?php

namespace App\Repositories\Contracts;

use App\Models\Course\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for Course data access.
 *
 * This contract defines the standard methods that any course repository
 * implementation must provide. It ensures a consistent API for interacting
 * with course data.
 */
interface CourseRepositoryInterface
{
    /**
     * Retrieve a course by its ID, including its modules and lessons.
     *
     * @param int $id
     * @return Course|null
     */
    public function findById(int $id): ?Course;

    /**
     * Retrieve all courses, with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allPaginated(int $perPage = 15);

    /**
     * Create a new course.
     *
     * @param array $data
     * @return Course
     */
    public function create(array $data): Course;

    /**
     * Update an existing course.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a course by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Find all courses created by a specific instructor.
     *
     * @param int $instructorId
     * @return Collection
     */
    public function findByInstructor(int $instructorId): Collection;

    /**
     * Enroll a user in a course.
     *
     * @param User $user
     * @param Course $course
     * @return \App\Models\Course\Enrollment
     */
    public function enroll(User $user, Course $course): \App\Models\Course\Enrollment;
}