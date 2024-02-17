<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\DeviceWithMeasurementParameters;

use App\Infrastructure\Messenger\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class DeviceWithMeasurementParametersQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $id
    ) {
    }
}
