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

class ProjectController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function __construct(
        protected ProjectService $projectService
    ) {
    }

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

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject($request->user(), $request->validated());

        return $this->successResponse(
            new ProjectResource($project),
            'Project created successfully',
            201
        );
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return $this->successResponse(
            new ProjectResource($project->loadCount('tasks')),
            'Project retrieved successfully'
        );
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $updatedProject = $this->projectService->updateProject($project, $request->validated());

        return $this->successResponse(
            new ProjectResource($updatedProject),
            'Project updated successfully'
        );
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $this->projectService->deleteProject($project);

        return $this->successResponse(null, 'Project deleted successfully');
    }
}