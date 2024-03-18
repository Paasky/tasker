<?php

namespace App\Enums;

enum TaskStatus: string
{
    case BACKLOG = 'backlog';
    case TODO = 'todo';
    case IN_PROGRESS = 'in-progress';
    case READY_FOR_QA = 'ready-for-qa';
    case IN_QA = 'in-qa';
    case READY_TO_DEPLOY = 'ready-to-deploy';
    case DEPLOYED = 'deployed';
    case DONE = 'done';
}
