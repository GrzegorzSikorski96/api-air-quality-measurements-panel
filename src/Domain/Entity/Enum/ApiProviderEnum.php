<?php

declare(strict_types=1);

namespace App\Domain\Entity\Enum;

enum ApiProviderEnum: string
{
    case LOOKO2 = 'looko2';
    public static function getAllApiProviders(): array
    {
        return array_values(array_column(ApiProviderEnum::cases(), 'value'));
    }
}
