<?php

namespace App\Http\Requests\Course;

use App\Models\Course\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        $course = $this->route('course');

        // The user must be authenticated to enroll.
        if (!$user) {
            return false;
        }

        // Check if the user is already enrolled in the course.
        $isAlreadyEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        // The user is authorized if they are NOT already enrolled.
        return !$isAlreadyEnrolled;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Typically, there are no specific rules needed for a simple enrollment request,
     * as the user and course IDs are taken from the authenticated user and the route.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // No validation rules needed for this specific request.
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        // Although there are no rules, the authorize method can fail.
        // We can't customize the "This action is unauthorized." message directly here,
        // but it's good practice to know this method exists.
        return [];
    }
}