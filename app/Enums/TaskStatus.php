<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum TaskStatus: string
{
    use TaskerEnum;

    case BACKLOG = 'backlog';
    case CANCELLED = 'cancelled';
    case TODO = 'todo';
    case IN_PROGRESS = 'in-progress';
    case READY_FOR_QA = 'ready-for-qa';
    case IN_QA = 'in-qa';
    case READY_TO_DEPLOY = 'ready-to-deploy';
    case DEPLOYED = 'deployed';
    case DONE = 'done';

    public function title(): string
    {
        return __(
            match ($this) {
                self::READY_FOR_QA => 'Ready For QA',
                self::IN_QA => 'In QA',
                default => Str::title(str_replace('_', ' ', $this->name)),
            }
        );
    }
}
