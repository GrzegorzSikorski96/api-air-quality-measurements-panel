<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Measurements;

use App\Infrastructure\Messenger\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final class MeasurementsQuery implements QueryInterface
{
    public function __construct(
        public readonly Uuid $deviceId,
        public readonly Uuid $measurementParameterId,
        public readonly \DateTime $startDateTime,
        public readonly ?\DateTime $endDateTime
    ) {
    }
}
