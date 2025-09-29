<?php

namespace App\Http\Controllers\API\V1\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Course\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'User is already enrolled in this course.'], 409);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
        ]);

        return response()->json($enrollment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Enrollment $enrollment)
    {
        // Add authorization logic here to ensure the user can view this enrollment
        return $enrollment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Enrollment $enrollment)
    {
        // Add authorization logic here
        $enrollment->delete();

        return response()->json(null, 204);
    }
}