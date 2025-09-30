<?php

namespace App\Observers;

use App\Models\Course\Course;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Observer for the Course model.
 */
class CourseObserver
{
    /**
     * Handle the Course "creating" event.
     *
     * @param  \App\Models\Course\Course  $course
     * @return void
     */
    public function creating(Course $course): void
    {
        // Automatically generate a slug from the title
        $course->slug = Str::slug($course->title);
    }

    /**
     * Handle the Course "updating" event.
     *
     * @param  \App\Models\Course\Course  $course
     * @return void
     */
    public function updating(Course $course): void
    {
        // If the title is changed, update the slug as well
        if ($course->isDirty('title')) {
            $course->slug = Str::slug($course->title);
        }
    }

    /**
     * Handle the Course "saved" event (fired on create and update).
     *
     * @param  \App\Models\Course\Course  $course
     * @return void
     */
    public function saved(Course $course): void
    {
        // When a course is updated, we should clear any related cache
        // to ensure the frontend displays the latest data.
        Cache::forget('course_' . $course->id);
        Cache::forget('all_courses_page_1'); // Example of clearing a list cache
    }

    /**
     * Handle the Course "deleted" event.
     *
     * @param  \App\Models\Course\Course  $course
     * @return void
     */
    public function deleted(Course $course): void
    {
        // Also clear the cache when a course is deleted
        Cache::forget('course_' . $course->id);
    }
}