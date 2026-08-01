<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct( protected DashboardService $dashboardService ) {}

    #[OA\Get(
        path: "/dashboard",
        summary: "Get aggregate dashboard statistics",
        security: [["bearerAuth" => []]],
        tags: ["Dashboard"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Dashboard metrics retrieved successfully",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Dashboard metrics retrieved successfully",
                        "data" => [
                            "projects" => ["total_projects" => 10, "active_projects" => 6],
                            "tasks" => ["total_tasks" => 35, "completed_tasks" => 15, "pending_tasks" => 20, "overdue_tasks" => 3]
                        ]
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $stats = $this->dashboardService->getUserDashboardStats($request->user());

        return $this->successResponse(
            new DashboardResource($stats),
            'Dashboard metrics retrieved successfully'
        );
    }
}