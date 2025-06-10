<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Measurement;
use DateTime;
use Symfony\Component\Uid\Uuid;

interface MeasurementRepositoryInterface
{
    public function findAll(): array;

    public function findOne(Uuid $measurementId): ?Measurement;

    public function get(Uuid $measurementId): Measurement;

    public function save(Measurement $measurement): void;

    public function findByDeviceAndParameterInTimeRange(Uuid $deviceId, Uuid $measurementParameterId, DateTime $startDateTime, ?DateTime $endDateTime = null): array;
}
