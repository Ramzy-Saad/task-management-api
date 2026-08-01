<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $taskTitles = [
            'Design Database Schema & ERD',
            'Setup Sanctum Authentication API',
            'Write OpenAPI/Swagger Documentation',
            'Implement Payment Gateway Integration',
            'Optimize SQL Query Performance',
            'Build User Dashboard Endpoints',
            'Configure Redis Queue Worker',
            'Set up Automated CI/CD Pipeline',
            'Create Unit & Feature Tests',
            'Implement ElasticSearch Indexing',
            'Refactor Legacy Controllers',
            'Fix CORS & Sanctum Cookie Headers',
        ];

        $status = fake()->randomElement(TaskStatus::cases());

        // Generate due dates: some overdue, some future, some null
        $dueDateOption = fake()->randomElement(['past', 'future', 'null']);
        $dueDate = match ($dueDateOption) {
            'past' => fake()->dateTimeBetween('-1 month', '-1 day'),
            'future' => fake()->dateTimeBetween('+1 day', '+2 months'),
            'null' => null,
        };

        return [
            'project_id' => Project::factory(),
            'title' => fake()->randomElement($taskTitles),
            'description' => fake()->paragraph(2),
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'status' => $status,
            'due_date' => $dueDate,
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
    }

    public function overdue(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TaskStatus::TODO,
            'due_date' => fake()->dateTimeBetween('-10 days', '-1 day'),
        ]);
    }

    public function done(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TaskStatus::DONE,
            'due_date' => fake()->dateTimeBetween('-5 days', '+5 days'),
        ]);
    }
}