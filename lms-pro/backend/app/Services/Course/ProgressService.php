<?php

namespace App\Services\Course;

use App\Models\Course\Course;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Exception;

class ProgressService
{
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Get the overall progress for a user in a specific course.
     *
     * @param User $user
     * @param Course $course
     * @return array
     * @throws Exception
     */
    public function getUserProgressForCourse(User $user, Course $course): array
    {
        $enrollment = $this->enrollmentRepository->findByUserAndCourse($user, $course);
        if (!$enrollment) {
            throw new Exception('User is not enrolled in this course.');
        }

        $totalLessons = $course->lessons()->count();
        $completedLessons = $enrollment->completedLessons()->count();

        $percentage = ($totalLessons > 0) ? ($completedLessons / $totalLessons) * 100 : 0;

        // Update the main progress field on the enrollment record
        $this->enrollmentRepository->updateProgress($enrollment->id, $percentage);

        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'percentage' => round($percentage, 2),
        ];
    }

    /**
     * Mark a lesson as complete for a user.
     *
     * @param User $user
     * @param int $lessonId
     * @return void
     * @throws Exception
     */
    public function updateLessonProgress(User $user, int $lessonId, string $status): void
    {
        $enrollment = $user->enrollments()->whereHas('course.lessons', function ($query) use ($lessonId) {
            $query->where('id', $lessonId);
        })->first();

        if (!$enrollment) {
            throw new Exception('User is not enrolled in the course containing this lesson.');
        }

        // Attach or detach from the pivot table
        if ($status === 'completed') {
            $enrollment->completedLessons()->syncWithoutDetaching([$lessonId]);
        } else {
            $enrollment->completedLessons()->detach($lessonId);
        }

        // Recalculate and save the overall progress after the update
        $this->getUserProgressForCourse($user, $enrollment->course);
    }
}