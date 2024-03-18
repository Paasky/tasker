<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::factory()
            ->count(config('tasker.max_tasks_per_user') - 1)
            ->create(['user_id' => User::firstOrFail()]);
    }
}
