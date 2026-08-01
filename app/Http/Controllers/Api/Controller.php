<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Task Management RESTful API",
    description: "Production-ready REST API for Project & Task Management built with Laravel and Sanctum."
)]
#[OA\Server(
    url: "http://localhost:8000/api/",
    description: "Local Development Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    name: "Authorization",
    in: "header",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Enter your Bearer Token in the format: Bearer <token>"
)]
#[OA\Tag(name: "Authentication", description: "Endpoints for User Registration, Login & Logout")]
#[OA\Tag(name: "Projects", description: "Endpoints for User Project Management")]
#[OA\Tag(name: "Tasks", description: "Endpoints for Task Management with Filtering & Search")]
#[OA\Tag(name: "Dashboard", description: "Aggregated Analytics and Metrics Endpoint")]
abstract class Controller extends BaseController
{
}