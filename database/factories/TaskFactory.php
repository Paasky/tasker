<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fn() => User::factory()->create(),
            'title' => Str::title($this->faker->words(rand(1, 4), true)),
            'description' => rand(0, 1)
                ? $this->faker->paragraph
                : null,
            'priority' => Arr::random(TaskPriority::cases()),
            'status' => Arr::random(TaskStatus::cases()),
            'due' => rand(0, 1)
                ? new Carbon($this->faker->dateTimeBetween('+1 day', '+30 days'))
                : null,
        ];
    }
}
