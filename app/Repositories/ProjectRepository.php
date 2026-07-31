<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectRepository
{
    public function getPaginatedForUser(int $userId, int $perPage )
    {
        return Project::where('user_id', $userId)
            ->withCount('tasks')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return Project::create($data);
    }

    public function update(Project $project, array $data)
    {
        $project->update($data);
        return $project->fresh();
    }

    public function delete(Project $project)
    {
        return $project->delete();
    }
}