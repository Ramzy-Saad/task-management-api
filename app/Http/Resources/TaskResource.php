<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority?->value,
            'status' => $this->status?->value,
            'due_date' => $this->due_date?->format('Y/M/d'),
            'is_overdue' => $this->due_date && $this->due_date->isPast() && $this->status->value !== 'done',
            'created_at' => $this->created_at->format('Y/M/d'),
            'updated_at' => $this->updated_at->format('Y/M/d'),
            'project' => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}