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
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function __construct(
        protected TaskService $taskService
    ) {
    }
    #[OA\Get(
        path: "/tasks",
        summary: "List and filter user tasks",
        description: "Retrieve a paginated list of tasks belonging to the authenticated user with optional filtering and sorting.",
        security: [["bearerAuth" => []]],
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(name: "project_id", in: "query", description: "Filter by project ID", required: false, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "status", in: "query", description: "Filter by task status", required: false, schema: new OA\Schema(type: "string", enum: ["todo", "in_progress", "done"])),
            new OA\Parameter(name: "priority", in: "query", description: "Filter by priority", required: false, schema: new OA\Schema(type: "string", enum: ["low", "medium", "high"])),
            new OA\Parameter(name: "search", in: "query", description: "Search term for title or description", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "sort_by", in: "query", description: "Sort column (due_date, created_at, priority)", required: false, schema: new OA\Schema(type: "string", default: "created_at")),
            new OA\Parameter(name: "sort_order", in: "query", description: "Sort direction (asc, desc)", required: false, schema: new OA\Schema(type: "string", default: "desc")),
            new OA\Parameter(name: "per_page", in: "query", description: "Items per page", required: false, schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tasks retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Tasks retrieved successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "tasks", type: "array", items: new OA\Items(type: "object")),
                                new OA\Property(
                                    property: "pagination",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "total", type: "integer", example: 45),
                                        new OA\Property(property: "per_page", type: "integer", example: 15),
                                        new OA\Property(property: "current_page", type: "integer", example: 1),
                                        new OA\Property(property: "last_page", type: "integer", example: 3)
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]

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
    #[OA\Post(
        path: "/tasks",
        summary: "Create a new task",
        description: "Creates a task assigned to a specific project owned by the user.",
        security: [["bearerAuth" => []]],
        tags: ["Tasks"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreTaskRequest")
        ),
        responses: [
            new OA\Response(response: 201, description: "Task created successfully"),
            new OA\Response(response: 403, description: "Forbidden - Project does not belong to user"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->user(), $request->validated());

        return $this->successResponse(
            new TaskResource($task),
            'Task created successfully',
            201
        );
    }
    #[OA\Put(
        path: "/tasks/{id}",
        summary: "Update an existing task",
        security: [["bearerAuth" => []]],
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateTaskRequest")
        ),
        responses: [
            new OA\Response(response: 200, description: "Task updated successfully"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $updatedTask = $this->taskService->updateTask($task, $request->validated());

        return $this->successResponse(
            new TaskResource($updatedTask),
            'Task updated successfully'
        );
    }

    #[OA\Delete(
        path: "/tasks/{id}",
        summary: "Delete a task",
        security: [["bearerAuth" => []]],
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Task deleted successfully"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 404, description: "Task not found")
        ]
    )]
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return $this->successResponse(null, 'Task deleted successfully');
    }
}