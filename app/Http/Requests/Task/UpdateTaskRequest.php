<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: "UpdateTaskRequest",
    title: "Update Task Request Body",
    properties: [
        new OA\Property(property: "title", type: "string", example: "Setup Database Migrations and Seeders"),
        new OA\Property(property: "description", type: "string", nullable: true, example: "Updated description for migration task."),
        new OA\Property(property: "priority", type: "string", enum: ["low", "medium", "high"], example: "high"),
        new OA\Property(property: "status", type: "string", enum: ["todo", "in_progress", "done"], example: "in_progress"),
        new OA\Property(property: "due_date", type: "string", format: "date-time", nullable: true, example: "2026-08-20T18:00:00Z"),
    ]
)]

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'priority' => ['sometimes', 'required', 'string', Rule::enum(TaskPriority::class)],
            'status' => ['sometimes', 'required', 'string', Rule::enum(TaskStatus::class)],
            'due_date' => ['nullable', 'date'],
        ];
    }
}