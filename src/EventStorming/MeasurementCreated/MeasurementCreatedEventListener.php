<?php

declare(strict_types=1);

namespace App\EventStorming\MeasurementCreated;

use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Infrastructure\Messenger\Command\CommandBus;
use App\Infrastructure\Messenger\Event\EventListenerInterface;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceCommand;

final readonly class MeasurementCreatedEventListener implements EventListenerInterface
{
    public function __construct(
        private DeviceMeasurementParameterRepositoryInterface $deviceMeasurementParameterRepository,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(MeasurementCreatedEvent $event): void
    {
        $deviceMeasurementParameter = $this->deviceMeasurementParameterRepository
            ->findOneByDeviceIdAndMeasurementParameterId(
                $event->deviceId,
                $event->measurementParameterId
            );

        if (! is_null($deviceMeasurementParameter)) {
            return;
        }

        $assignMeasurementParameterToDeviceCommand = new AssignMeasurementParameterToDeviceCommand(
            $event->measurementParameterId,
            $event->deviceId
        );
        $this->commandBus->dispatch($assignMeasurementParameterToDeviceCommand);
    }
}
