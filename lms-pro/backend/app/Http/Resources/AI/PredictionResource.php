<?php

namespace App\Http\Resources\AI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PredictionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'input' => $this->input, // The data that was sent for prediction
            'output' => $this->output, // The result from the model
            'created_at' => $this->created_at->toIso8601String(),

            // Conditionally load the model that was used for this prediction
            'model' => new ModelResource($this->whenLoaded('model')),
        ];
    }
}