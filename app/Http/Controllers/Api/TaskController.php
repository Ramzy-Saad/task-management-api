<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\FilterTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function __construct(
        protected TaskService $taskService
    ) {
    }

    public function index(FilterTaskRequest $request): JsonResponse
    {
        $tasks = $this->taskService->getTasksForUser($request->user(), $request->validated());

        return $this->successResponse([
            'tasks' => TaskResource::collection($tasks),
            'pagination' => [
                'total' => $tasks->total(),
                'per_page' => $tasks->perPage(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
            ],
        ], 'Tasks retrieved successfully');
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->user(), $request->validated());

        return $this->successResponse(
            new TaskResource($task),
            'Task created successfully',
            201
        );
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return $this->successResponse(
            new TaskResource($task->load('project')),
            'Task retrieved successfully'
        );
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $updatedTask = $this->taskService->updateTask($task, $request->validated());

        return $this->successResponse(
            new TaskResource($updatedTask),
            'Task updated successfully'
        );
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return $this->successResponse(null, 'Task deleted successfully');
    }
}