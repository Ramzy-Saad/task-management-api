<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Project::factory()->active()->create(['user_id' => $user->id]);
            Project::factory()->completed()->create(['user_id' => $user->id]);
            Project::factory()->archived()->create(['user_id' => $user->id]);

            Project::factory()->count(2)->create(['user_id' => $user->id]);
        }
    }
}