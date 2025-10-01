<?php

namespace App\Http\Requests\AI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ImageClassificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Any authenticated user can attempt to use this AI feature.
        // Finer-grained control can be handled by the RateLimitAI middleware.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Ensure the 'image' field is present, is a file, is an image, and has a max size of 5MB.
            'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            // 'top_k' is an optional parameter to specify how many top results to return.
            'top_k' => ['sometimes', 'integer', 'min:1', 'max:10'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'image.required' => 'An image file is required for classification.',
            'image.image' => 'The uploaded file must be a valid image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 5 megabytes.',
        ];
    }
}