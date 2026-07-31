<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository
{
    public function getFilteredTasksForUser(int $userId, array $filters): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;

        return Task::query()
            ->whereHas('project', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->when(isset($filters['project_id']), fn($q) => $q->where('project_id', $filters['project_id']))
            ->status($filters['status'] ?? null)
            ->priority($filters['priority'] ?? null)
            ->search($filters['search'] ?? null)
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}