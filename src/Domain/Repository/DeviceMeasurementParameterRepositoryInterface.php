<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\DeviceMeasurementParameter;
use Symfony\Component\Uid\Uuid;

interface DeviceMeasurementParameterRepositoryInterface
{
    public function findByDeviceId(Uuid $deviceId): array;

    public function save(DeviceMeasurementParameter $deviceMeasurementParameter): void;

    public function get(Uuid $deviceMeasurementParameterId): DeviceMeasurementParameter;

    public function findOne(Uuid $deviceMeasurementParameterId): ?DeviceMeasurementParameter;

    public function findOneByDeviceIdAndMeasurementParameterId(
        Uuid $deviceId,
        Uuid $measurementParameterId
    ): ?DeviceMeasurementParameter;
}
