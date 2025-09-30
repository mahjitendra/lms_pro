<?php

namespace App\Observers;

use App\Events\User\UserEnrolled;
use App\Models\Course\Enrollment;
use Illuminate\Support\Facades\Log;

/**
 * Observer for the Enrollment model.
 */
class EnrollmentObserver
{
    /**
     * Handle the Enrollment "created" event.
     *
     * This is the perfect place to dispatch an event to notify the rest of the application
     * that a user has enrolled in a course.
     *
     * @param  \App\Models\Course\Enrollment  $enrollment
     * @return void
     */
    public function created(Enrollment $enrollment): void
    {
        // Dispatch the UserEnrolled event.
        // We load the relationships to ensure the event has all the data it needs.
        $enrollment->load(['user', 'course']);

        UserEnrolled::dispatch($enrollment->user, $enrollment->course, $enrollment);

        Log::info("User ID: {$enrollment->user_id} has enrolled in Course ID: {$enrollment->course_id}. UserEnrolled event dispatched.");
    }

    /**
     * Handle the Enrollment "deleting" event.
     *
     * @param  \App\Models\Course\Enrollment  $enrollment
     * @return void
     */
    public function deleting(Enrollment $enrollment): void
    {
        // Here you could add logic to clean up related data, such as progress or grades,
        // before the enrollment is removed.
        Log::info("User ID: {$enrollment->user_id} is being unenrolled from Course ID: {$enrollment->course_id}.");
    }
}