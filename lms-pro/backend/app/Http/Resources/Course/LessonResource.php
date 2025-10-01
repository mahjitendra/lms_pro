<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'content_type' => $this->content_type,
            'order' => $this->order,
            'duration_in_minutes' => $this->duration,

            // You could add a 'content' attribute here that provides the video URL or text content.
            // Be careful not to expose sensitive information.
            // 'content' => $this->when($request->user()->can('view', $this), $this->content),
        ];
    }
}