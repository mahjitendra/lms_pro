<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Only users with the 'create-courses' permission can perform this action.
        // This uses the HasPermissions trait we defined earlier.
        return Auth::user()->hasPermissionTo('create-courses');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'level' => ['required', 'string', 'in:beginner,intermediate,advanced'],
            'price' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}