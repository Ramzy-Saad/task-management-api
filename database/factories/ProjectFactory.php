<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $projectNames = [
            'E-Commerce Platform Redesign',
            'Mobile Banking Application',
            'Healthcare Patient Portal',
            'AI-Powered Analytics Dashboard',
            'Supply Chain Tracking System',
            'Real-Time Chat & Collaboration Tool',
            'HR Operations & Payroll Management',
            'Inventory & Warehouse API',
            'Customer Support Ticketing Hub',
            'Social Media Content Planner',
        ];

        return [
            'user_id'     => User::factory(),
            'name'        => fake()->randomElement($projectNames) . ' (' . fake()->company() . ')',
            'description' => fake()->paragraph(3),
            'status'      => fake()->randomElement(ProjectStatus::cases()),
            'created_at'  => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at'  => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::ACTIVE,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::COMPLETED,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::ARCHIVED,
        ]);
    }
}