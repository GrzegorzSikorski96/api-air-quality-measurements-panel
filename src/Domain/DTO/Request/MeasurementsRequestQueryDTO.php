<?php

declare(strict_types=1);

namespace App\Domain\DTO\Request;

use App\Domain\Validator\DeviceId\IsDeviceIdExists;
use App\Domain\Validator\MeasurementParameterId\IsMeasurementParameterIdExists;
use Symfony\Component\Uid\Uuid;

final class MeasurementsRequestQueryDTO
{
    #[IsDeviceIdExists(404)]
    public Uuid $deviceId;
    #[IsMeasurementParameterIdExists(404)]
    public Uuid $measurementParameterId;
    public \DateTime $startDateTime;
    public ?\DateTime $endDateTime = null;

    public function __construct(
        string $deviceId,
        string $measurementParameterId,
        string $startDateTime,
        ?string $endDateTime = null
    ) {
        $this->deviceId = Uuid::fromString($deviceId);
        $this->measurementParameterId = Uuid::fromString($measurementParameterId);
        $this->startDateTime = new \DateTime($startDateTime);

        if (!is_null($endDateTime)) {
            $this->endDateTime = new \DateTime($endDateTime);
        }
    }
}
