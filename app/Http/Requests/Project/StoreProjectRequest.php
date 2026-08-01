<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StoreProjectRequest",
    title: "Store Project Request Body",
    required: ["name"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "E-Commerce Redesign"),
        new OA\Property(property: "description", type: "string", nullable: true, example: "Full overhaul of frontend store and checkout system."),
        new OA\Property(property: "status", type: "string", enum: ["active", "completed", "archived"], example: "active"),
    ]
)]
class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization is handled via Policy in Controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'string', Rule::enum(ProjectStatus::class)],
        ];
    }
}