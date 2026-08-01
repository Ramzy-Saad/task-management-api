<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            Task::factory()->overdue()->create([
                'project_id' => $project->id,
                'priority' => TaskPriority::HIGH,
            ]);

            Task::factory()->count(2)->done()->create([
                'project_id' => $project->id,
            ]);

            Task::factory()->count(2)->create([
                'project_id' => $project->id,
                'status' => TaskStatus::TODO,
            ]);

            Task::factory()->create([
                'project_id' => $project->id,
                'status' => TaskStatus::IN_PROGRESS,
                'priority' => TaskPriority::MEDIUM,
                'due_date' => now()->addDays(3),
            ]);
        }
    }
}