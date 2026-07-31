<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    /**
     * Get aggregate metrics for the dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        $stats = $this->dashboardService->getUserDashboardStats($request->user());

        return $this->successResponse(
            new DashboardResource($stats),
            'Dashboard metrics retrieved successfully'
        );
    }
}