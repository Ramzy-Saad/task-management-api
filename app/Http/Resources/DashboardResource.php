<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'projects' => [
                'total_projects' => $this['projects']['total'],
                'active_projects' => $this['projects']['active'],
            ],
            'tasks' => [
                'total_tasks' => $this['tasks']['total'],
                'completed_tasks' => $this['tasks']['completed'],
                'pending_tasks' => $this['tasks']['pending'],
                'overdue_tasks' => $this['tasks']['overdue'],
            ],
        ];
    }
}