<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Repositories\ProjectRepository;

class ProjectService
{
    public function __construct(
        protected ProjectRepository $projectRepository
    ) {
    }

    public function getUserProjects(User $user, int $perPage)
    {
        return $this->projectRepository->getPaginatedForUser($user->id, $perPage);
    }

    public function createProject(User $user, array $data)
    {
        $data['user_id'] = $user->id;
        return $this->projectRepository->create($data);
    }

    public function updateProject(Project $project, array $data)
    {
        return $this->projectRepository->update($project, $data);
    }

    public function deleteProject(Project $project)
    {
        return $this->projectRepository->delete($project);
    }
}