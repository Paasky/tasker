<?php


use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function testViewAny(): void
    {
        $user = User::factory()->create();

        // Anyone can view any
        $this->assertTrue(
            (new TaskPolicy())->viewAny($user)
        );

        $this->assertTrue(
            $user->can('viewAny', Task::class)
        );
    }

    public function testView(): void
    {
        $owner = User::factory()->create();
        $hacker = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        // Task Owner can view
        $this->assertTrue(
            (new TaskPolicy())->view($owner, $task)
        );

        $this->assertTrue(
            $owner->can('view', $task)
        );

        // No one else can view
        $this->assertFalse(
            (new TaskPolicy())->view($hacker, $task)
        );

        $this->assertFalse(
            $hacker->can('view', $task)
        );
    }

    public function testCreate(): void
    {
        $user = User::factory()->create();

        // No tasks -> OK
        $this->assertTrue(
            (new TaskPolicy())->create($user)
        );

        $this->assertTrue(
            $user->can('create', Task::class)
        );

        // Not at max tasks -> OK
        Task::factory()
            ->count(config('tasker.max_tasks_per_user') - 1)
            ->create(['user_id' => $user->id]);

        $this->assertTrue(
            (new TaskPolicy())->create($user)
        );

        $this->assertTrue(
            $user->can('create', Task::class)
        );

        // At max tasks -> Not OK
        Task::factory()->create(['user_id' => $user->id]);
        $user->refresh();

        $this->assertEquals(
            new Response(
                false,
                'Cannot have more than 10 tasks'
            ),
            (new TaskPolicy())->create($user)
        );

        $this->assertFalse(
            $user->can('create', Task::class)
        );

        // At max + 1 tasks -> Not OK
        Task::factory()->create(['user_id' => $user->id]);
        $user->refresh();


        $this->assertEquals(
            new Response(
                false,
                'Cannot have more than 10 tasks'
            ),
            (new TaskPolicy())->create($user)
        );

        $this->assertFalse(
            $user->can('create', Task::class)
        );
    }

    public function testUpdate(): void
    {
        $owner = User::factory()->create();
        $hacker = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        // Task Owner can update
        $this->assertTrue(
            (new TaskPolicy())->update($owner, $task)
        );

        $this->assertTrue(
            $owner->can('update', $task)
        );

        // No one else can update
        $this->assertFalse(
            (new TaskPolicy())->update($hacker, $task)
        );

        $this->assertFalse(
            $hacker->can('update', $task)
        );
    }

    public function testDelete(): void
    {
        $owner = User::factory()->create();
        $hacker = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        // Task Owner can delete
        $this->assertTrue(
            (new TaskPolicy())->delete($owner, $task)
        );

        $this->assertTrue(
            $owner->can('delete', $task)
        );

        // No one else can delete
        $this->assertFalse(
            (new TaskPolicy())->delete($hacker, $task)
        );

        $this->assertFalse(
            $hacker->can('delete', $task)
        );
    }

    public function testRestore(): void
    {
        $owner = User::factory()->create();
        $hacker = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        // Task Owner can restore
        $this->assertTrue(
            (new TaskPolicy())->restore($owner, $task)
        );

        $this->assertTrue(
            $owner->can('restore', $task)
        );

        // No one else can restore
        $this->assertFalse(
            (new TaskPolicy())->restore($hacker, $task)
        );

        $this->assertFalse(
            $hacker->can('restore', $task)
        );
    }

    public function testForceDelete(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        // No one can force delete
        $this->assertFalse(
            (new TaskPolicy())->forceDelete($user, $task)
        );

        $this->assertFalse(
            $user->can('forceDelete', $task)
        );
    }
}
