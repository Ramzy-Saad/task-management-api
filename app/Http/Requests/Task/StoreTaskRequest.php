<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StoreTaskRequest",
    title: "Store Task Request Body",
    required: ["project_id", "title"],
    properties: [
        new OA\Property(property: "A", type: "integer", example: 1),
        new OA\Property(property: "title", type: "string", example: "Setup Database Migrations"),
        new OA\Property(property: "description", type: "string", nullable: true, example: "Write migrations for projects and tasks tables."),
        new OA\Property(property: "priority", type: "string", enum: ["low", "medium", "high"], example: "high"),
        new OA\Property(property: "status", type: "string", enum: ["todo", "in_progress", "done"], example: "todo"),
        new OA\Property(property: "due_date", type: "string", format: "date-time", nullable: true, example: "2026-08-15T12:00:00Z"),
    ]
)]

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'priority' => ['nullable', 'string', Rule::enum(TaskPriority::class)],
            'status' => ['nullable', 'string', Rule::enum(TaskStatus::class)],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}