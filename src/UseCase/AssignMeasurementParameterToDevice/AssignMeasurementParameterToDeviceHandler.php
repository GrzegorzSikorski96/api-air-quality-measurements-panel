<?php

declare(strict_types=1);

namespace App\UseCase\AssignMeasurementParameterToDevice;

use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\EventStorming\MeasurementParameterAssignedToDevice\MeasurementParameterAssignedToDeviceEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Infrastructure\Messenger\Event\EventBus;

final readonly class AssignMeasurementParameterToDeviceHandler implements CommandHandlerInterface
{
    public function __construct(
        private DeviceMeasurementParameterRepositoryInterface $deviceMeasurementParameterRepository,
        private EventBus $eventBus,
    ) {
    }

    public function __invoke(AssignMeasurementParameterToDeviceCommand $command): void
    {
        $deviceMeasurementParameter = new DeviceMeasurementParameter(
            deviceId: $command->deviceId,
            measurementParameterId: $command->measurementParameterId,
        );

        $this->deviceMeasurementParameterRepository->save($deviceMeasurementParameter);

        $measurementParameterAssignedToDeviceEvent = new MeasurementParameterAssignedToDeviceEvent(
            $command->measurementParameterId,
            $command->deviceId
        );
        $this->eventBus->dispatch($measurementParameterAssignedToDeviceEvent);
    }
}
