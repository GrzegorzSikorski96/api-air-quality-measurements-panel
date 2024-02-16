<?php

declare(strict_types=1);

namespace App\UseCase\AssignMeasurementParameterToDevice;

use App\Domain\Validator\DeviceId\IsDeviceIdExists;
use App\Domain\Validator\MeasurementParameterId\IsMeasurementParameterIdExists;
use App\Infrastructure\Messenger\Command\AsyncCommandInterface;
use App\Infrastructure\Messenger\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class AssignMeasurementParameterToDeviceCommand implements AsyncCommandInterface, CommandInterface
{
    public function __construct(
        #[IsMeasurementParameterIdExists(404)]
        public Uuid $measurementParameterId,
        #[IsDeviceIdExists(404)]
        public Uuid $deviceId
    ) {
    }
}
