<?php

declare(strict_types=1);

namespace App\Enum;

class LocationEnum
{
    const TOP = 'top';
    const BOTTOM = 'bottom';

    public static function choices(): array
    {
        return [
            self::TOP    => 'enum.location.top',
            self::BOTTOM => 'enum.location.bottom',
        ];
    }
}
