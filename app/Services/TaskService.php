<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Auth\Access\AuthorizationException;

class TaskService
{
    public function __construct(
        protected TaskRepository $taskRepository
    ) {
    }

    public function getTasksForUser(User $user, array $filters)
    {
        return $this->taskRepository->getFilteredTasksForUser($user->id, $filters);
    }

    public function createTask(User $user, array $data)
    {
        // Verify user owns the target project
        $project = Project::findOrFail($data['project_id']);
        if ($project->user_id !== $user->id) {
            throw new AuthorizationException('You do not own this project.');
        }

        return $this->taskRepository->create($data);
    }

    public function updateTask(Task $task, array $data)
    {
        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask(Task $task)
    {
        return $this->taskRepository->delete($task);
    }
}