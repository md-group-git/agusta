<?php

declare(strict_types=1);

namespace App\Enum;

class StockStatusEnum
{
    const IN_STOCK = 'instock';
    const AVAILABLE_ON_ORDER = 'onorder';
    const OUT_OF_STOCK = 'outofstock';

    public static function choices(): array
    {
        return [
            self::IN_STOCK           => 'enum.stock.instock',
            self::AVAILABLE_ON_ORDER => 'enum.stock.onorder',
            self::OUT_OF_STOCK       => 'enum.stock.outofstock',
        ];
    }
}
