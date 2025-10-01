<?php

namespace App\Http\Requests\AI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ModelTrainingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Only users with the 'train-models' permission (e.g., an admin or data scientist)
        // should be able to initiate training.
        return Auth::user()->hasPermissionTo('train-models');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Ensure the model and dataset exist in their respective tables.
            'model_id' => ['required', 'integer', 'exists:ai_models,id'],
            'dataset_id' => ['required', 'integer', 'exists:datasets,id'],
            // Hyperparameters should be an object (which translates to an associative array in PHP).
            'hyperparameters' => ['sometimes', 'array'],
        ];
    }
}