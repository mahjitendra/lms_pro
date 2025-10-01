<?php

namespace App\Http\Resources\AI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalysisResource extends JsonResource
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
            'content_type' => $this->getMorphClass(), // e.g., 'App\Models\Content\Video'
            'content_id' => $this->id,
            'status' => $this->processing_status,

            // The `analysis_data` field is expected to be a JSON column,
            // so it will be automatically cast to an array/object.
            'analysis_results' => $this->analysis_data,

            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}