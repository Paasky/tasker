<?php

namespace App\Enums;

enum TaskPriority: int
{
    use TaskerEnum;

    case NONE = 0;
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
    case URGENT = 4;
}
