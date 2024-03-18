<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Notifications\TaskNotification;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskUpdatedNotification implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    public function handle(TaskUpdated $event): void
    {
        $event->task->user->notify(
            new TaskNotification(
                __(
                    $event->task->deleted_at
                        ? 'Task :title deleted'
                        : 'Task :title updated',
                    ['title' => $event->task->title]
                ),
                $event->task
            )
        );
    }
}
