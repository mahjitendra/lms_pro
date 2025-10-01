<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'level' => $this->level,
            'price' => $this->price,
            'created_at' => $this->created_at->toIso8601String(),

            // Conditionally load relationships to prevent N+1 issues.
            // The 'instructor' relationship will be transformed by UserResource.
            'instructor' => new UserResource($this->whenLoaded('instructor')),

            // The 'modules' relationship will be a collection transformed by ModuleResource.
            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
        ];
    }
}