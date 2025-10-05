<?php

namespace App\Services\Course;

use App\Models\Course\Course;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Exception;

class EnrollmentService
{
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Enroll a user in a course.
     *
     * @param User $user
     * @param Course $course
     * @return \App\Models\Course\Enrollment
     * @throws Exception
     */
    public function enrollUserInCourse(User $user, Course $course)
    {
        // Check if the user is already enrolled
        if ($this->enrollmentRepository->findByUserAndCourse($user, $course)) {
            throw new Exception('User is already enrolled in this course.');
        }

        // Check for payment requirements (this is a simplified example)
        if ($course->price > 0 && !$user->hasPaidFor($course->id)) {
             // In a real app, hasPaidFor would check payment records.
            throw new Exception('This course requires payment before enrollment.');
        }

        // The created event on the Enrollment model will fire the UserEnrolled event
        return $this->enrollmentRepository->create($user, $course);
    }

    /**
     * Unenroll a user from a course.
     *
     * @param User $user
     * @param Course $course
     * @return bool
     * @throws Exception
     */
    public function unenrollUserFromCourse(User $user, Course $course): bool
    {
        $enrollment = $this->enrollmentRepository->findByUserAndCourse($user, $course);

        if (!$enrollment) {
            throw new Exception('User is not enrolled in this course.');
        }

        return $this->enrollmentRepository->delete($enrollment);
    }
}