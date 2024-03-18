<?php

namespace App\Enums;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait TaskerEnum
{
    public static function options(): array
    {
        return Arr::mapWithKeys(
            static::cases(),
            fn (self $status) => [$status->value => $status->title()]
        );
    }

    public function title(): string
    {
        return __(Str::title(str_replace('_', ' ', $this->name)));
    }
}
