<?php

namespace App\Services\Course;

use App\Models\Course\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get all courses with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllCourses(int $perPage = 15)
    {
        // Example of caching the main course catalog
        return Cache::remember('all_courses_page_' . request('page', 1), 60, function () use ($perPage) {
            return $this->courseRepository->allPaginated($perPage);
        });
    }

    /**
     * Get a single course by its ID.
     *
     * @param int $id
     * @return Course|null
     */
    public function getCourseById(int $id): ?Course
    {
        return Cache::remember('course_' . $id, 3600, function () use ($id) {
            return $this->courseRepository->findById($id);
        });
    }

    /**
     * Create a new course.
     *
     * @param array $data
     * @return Course
     */
    public function createCourse(array $data): Course
    {
        // Assign the currently authenticated user as the instructor.
        $data['instructor_id'] = Auth::id();

        $course = $this->courseRepository->create($data);

        // Clear cache after creating a new course
        Cache::flush(); // A more sophisticated strategy would be to tag caches

        return $course;
    }

    /**
     * Update an existing course.
     *
     * @param Course $course
     * @param array $data
     * @return Course
     */
    public function updateCourse(Course $course, array $data): Course
    {
        $this->courseRepository->update($course->id, $data);

        // Clear relevant caches
        Cache::forget('course_' . $course->id);
        Cache::flush();

        return $course->fresh();
    }

    /**
     * Delete a course.
     *
     * @param Course $course
     * @return void
     */
    public function deleteCourse(Course $course): void
    {
        $this->courseRepository->delete($course->id);
        Cache::flush();
    }
}