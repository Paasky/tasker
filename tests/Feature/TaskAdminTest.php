<?php


use App\Filament\Resources\TaskResource\Pages\CreateTask;
use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAdminTest extends TestCase
{
    use RefreshDatabase;

    public function testTable(): void
    {
        $this->be(User::factory()->create());

        $tasks = Task::factory()->count(11)->create();
        $deletedTask = Task::factory()->trashed()->create();

        $title = $tasks->first()->title;

        Livewire::test(ListTasks::class)
            ->assertCanSeeTableRecords($tasks->take(10))
            ->assertCanNotSeeTableRecords([$tasks->last(), $deletedTask])

            ->assertCanRenderTableColumn('title')

            ->sortTable('title')
            ->assertCanSeeTableRecords($tasks->sortBy('title')->take(10), inOrder: true)

            ->sortTable('title', 'desc')
            ->assertCanSeeTableRecords($tasks->sortByDesc('title')->take(10), inOrder: true)

            ->searchTable($title)
            ->assertCanSeeTableRecords($tasks->where('title', $title))
            ->assertCanNotSeeTableRecords($tasks->where('title', '!=', $title));
    }

    public function testCreate(): void
    {
        $this->be(User::factory()->create());

        Livewire::test(CreateTask::class)
            ->call('create')
            ->assertHasFormErrors([
                'user_id' => 'required',
                'title' => 'required',
            ])

            ->fillForm([
                'user_id' => 99999999,
                'title' => '1234567890123456789012345678901234567890123456789012345678901234567890' .    // 70 char
                    '12345678901234567890123456789012345678901234567890123456789012345678901234567890' . // 150 char
                    '12345678901234567890123456789012345678901234567890123456789012345678901234567890' . // 230 char
                    '12345678901234567890123456', // 256 char
                'due' => now()->subDay(),
            ])
            ->call('create')
            ->assertHasFormErrors([
                'user_id' => 'exists',
                'title' => 'max',
                'due' => 'after_or_equal',
            ]);
    }
}
