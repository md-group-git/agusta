<?php

declare(strict_types=1);

namespace App\Enum;

class RequestStatusEnum
{
    const NEW = 'new';
    const PROCESSING = 'processing';
    const PROCESSED = 'processed';

    public static function choices(): array
    {
        return [
            self::NEW        => 'enum.request.new',
            self::PROCESSING => 'enum.request.processing',
            self::PROCESSED  => 'enum.request.processed',
        ];
    }
}
