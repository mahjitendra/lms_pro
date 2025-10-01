<?php

namespace App\Http\Requests\AI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VideoAnalysisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
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
            // Max size of 100MB (102400 KB) for this example.
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo', 'max:102400'],
            'features' => ['sometimes', 'array'],
            // Ensure each feature in the array is a valid, known analysis type.
            'features.*' => ['string', Rule::in(['object_detection', 'face_recognition', 'transcription'])],
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
            'video.required' => 'A video file is required for analysis.',
            'video.mimetypes' => 'The uploaded file must be a valid video (mp4, mov, avi).',
            'video.max' => 'The video may not be greater than 100 megabytes.',
            'features.*.in' => 'The selected feature is not a valid analysis type.',
        ];
    }
}