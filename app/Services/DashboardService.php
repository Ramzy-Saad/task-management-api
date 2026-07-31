<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Retrieve aggregated statistics for the authenticated user.
     */
    public function getUserDashboardStats(User $user)
    {
        $userId = $user->id;
        $now = Carbon::now();

        // 1. Project Statistics
        $projectStats = Project::where('user_id', $userId)
            ->selectRaw('COUNT(*) as total_projects')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as active_projects', [ProjectStatus::ACTIVE->value])
            ->first();

        // 2. Task Statistics (for user's projects)
        $taskStats = Task::whereHas('project', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->selectRaw('COUNT(*) as total_tasks')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_tasks', [TaskStatus::DONE->value])
            ->selectRaw('SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as pending_tasks', [
                TaskStatus::TODO->value,
                TaskStatus::IN_PROGRESS->value,
            ])
            ->selectRaw('SUM(CASE WHEN due_date < ? AND status != ? THEN 1 ELSE 0 END) as overdue_tasks', [
                $now,
                TaskStatus::DONE->value,
            ])
            ->first();

        return [
            'projects' => [
                'total' => (int) ($projectStats->total_projects ?? 0),
                'active' => (int) ($projectStats->active_projects ?? 0),
            ],
            'tasks' => [
                'total' => (int) ($taskStats->total_tasks ?? 0),
                'completed' => (int) ($taskStats->completed_tasks ?? 0),
                'pending' => (int) ($taskStats->pending_tasks ?? 0),
                'overdue' => (int) ($taskStats->overdue_tasks ?? 0),
            ],
        ];
    }
}