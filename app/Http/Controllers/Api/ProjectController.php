<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProjectController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function __construct(
        protected ProjectService $projectService
    ) {
    }

    #[OA\Get(
        path: "/projects",
        summary: "List authenticated user projects",
        security: [["bearerAuth" => []]],
        tags: ["Projects"],
        parameters: [
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(response: 200, description: "Projects list retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $projects = $this->projectService->getUserProjects($request->user(), $perPage);

        return $this->successResponse([
            'projects' => ProjectResource::collection($projects),
            'pagination' => [
                'total' => $projects->total(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
            ],
        ], 'Projects retrieved successfully');
    }

    #[OA\Post(
        path: "/projects",
        summary: "Create a new project",
        security: [["bearerAuth" => []]],
        tags: ["Projects"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreProjectRequest")
        ),
        responses: [
            new OA\Response(response: 201, description: "Project created successfully"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject($request->user(), $request->validated());

        return $this->successResponse(
            new ProjectResource($project),
            'Project created successfully',
            201
        );
    }

    #[OA\Get(
        path: "/projects/{id}",
        summary: "Get specific project details",
        security: [["bearerAuth" => []]],
        tags: ["Projects"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Project details retrieved successfully"),
            new OA\Response(response: 403, description: "Forbidden - Not project owner"),
            new OA\Response(response: 404, description: "Project not found")
        ]
    )]
    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return $this->successResponse(
            new ProjectResource($project->loadCount('tasks')),
            'Project retrieved successfully'
        );
    }

    #[OA\Put(
        path: "/projects/{id}",
        summary: "Update an existing project",
        security: [["bearerAuth" => []]],
        tags: ["Projects"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreProjectRequest")
        ),
        responses: [
            new OA\Response(response: 200, description: "Project updated successfully"),
            new OA\Response(response: 403, description: "Forbidden")
        ]
    )]
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $updatedProject = $this->projectService->updateProject($project, $request->validated());

        return $this->successResponse(
            new ProjectResource($updatedProject),
            'Project updated successfully'
        );
    }

    #[OA\Delete(
        path: "/projects/{id}",
        summary: "Delete a project (Soft Delete)",
        security: [["bearerAuth" => []]],
        tags: ["Projects"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Project deleted successfully"),
            new OA\Response(response: 403, description: "Forbidden")
        ]
    )]
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $this->projectService->deleteProject($project);

        return $this->successResponse(null, 'Project deleted successfully');
    }
}