<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Get the course from the route parameter
        $course = $this->route('course');

        // Allow the action if the user has the general 'edit-courses' permission (e.g., an admin)
        // OR if the user is the specific instructor who owns this course.
        return Auth::user()->hasPermissionTo('edit-courses') || Auth::id() === $course->instructor_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * The 'sometimes' rule means the field is only validated if it is present in the request.
     * This is ideal for UPDATE operations.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'min:20'],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'level' => ['sometimes', 'required', 'string', 'in:beginner,intermediate,advanced'],
            'price' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}