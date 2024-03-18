<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Notifications\TaskNotification;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskCreatedNotification implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    public function handle(TaskCreated $event): void
    {
        $event->task->user->notify(
            new TaskNotification(
                __(
                    'Task :title created',
                    ['title' => $event->task->title]
                ),
                $event->task
            )
        );
    }
}
