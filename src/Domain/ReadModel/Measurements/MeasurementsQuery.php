<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Measurements;

use App\Infrastructure\Messenger\Query\QueryInterface;
use DateTime;
use Symfony\Component\Uid\Uuid;

final readonly class MeasurementsQuery implements QueryInterface
{
    public function __construct(
        public Uuid $deviceId,
        public Uuid $measurementParameterId,
        public DateTime $startDateTime,
        public ?DateTime $endDateTime,
    ) {
    }
}
