<?php

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Listeners\SendTaskCreatedNotification;
use App\Listeners\SendTaskUpdatedNotification;
use App\Models\Task;
use App\Notifications\TaskNotification;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TaskEventTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): void
    {
        Event::fake();
        Task::factory()->create();
        Event::assertDispatched(TaskCreated::class, 1);
        Event::assertNotDispatched(TaskUpdated::class);
    }

    public function testCreateNotification(): void
    {
        Queue::fake();
        Task::factory()->create();
        Queue::assertPushed(
            fn (CallQueuedListener $job) => $job->class === SendTaskCreatedNotification::class,
            1
        );
    }

    public function testUpdate(): void
    {
        Event::fake();
        $task = Task::factory()->createQuietly();
        $task->update(['title' => 'New Title']);
        Event::assertDispatched(TaskUpdated::class, 1);
        Event::assertNotDispatched(TaskCreated::class);
    }

    public function testUpdateNotification(): void
    {
        Queue::fake();
        $task = Task::factory()->createQuietly();
        $task->update(['title' => 'New Title']);
        Queue::assertPushed(
            fn (CallQueuedListener $job) => $job->class === SendTaskUpdatedNotification::class,
            1
        );
    }

    public function testDelete(): void
    {
        Event::fake();
        $task = Task::factory()->createQuietly();
        $task->delete();
        Event::assertDispatched(TaskUpdated::class, 1);
        Event::assertNotDispatched(TaskCreated::class);
    }

    public function testDeleteNotification(): void
    {
        Queue::fake();
        $task = Task::factory()->createQuietly();
        $task->delete();
        Queue::assertPushed(
            fn (CallQueuedListener $job) => $job->class === SendTaskUpdatedNotification::class,
            1
        );
    }

    public function testSendTaskCreatedNotification(): void
    {
        Notification::fake();
        $task = Task::factory()->createQuietly();
        (new SendTaskCreatedNotification)->handle(new TaskCreated($task));

        Notification::assertSentTo(
            [$task->user],
            TaskNotification::class,
            fn (TaskNotification $notification) => $notification->getTitle() === "Task $task->title created"
        );
    }

    public function testSendTaskUpdatedNotification(): void
    {
        Notification::fake();
        $task = Task::factory()->createQuietly();
        (new SendTaskUpdatedNotification)->handle(new TaskUpdated($task));

        Notification::assertSentTo(
            [$task->user],
            TaskNotification::class,
            fn (TaskNotification $notification) => $notification->getTitle() === "Task $task->title updated"
        );
    }

    public function testSendTaskDeletedNotification(): void
    {
        Notification::fake();
        $task = Task::factory()->createQuietly(['deleted_at' => now()]);
        (new SendTaskUpdatedNotification)->handle(new TaskUpdated($task));

        Notification::assertSentTo(
            [$task->user],
            TaskNotification::class,
            fn (TaskNotification $notification) => $notification->getTitle() === "Task $task->title deleted"
        );
    }
}
