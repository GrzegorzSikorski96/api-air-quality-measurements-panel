<?php

declare(strict_types=1);

namespace App\UseCase\CreateMeasurement;

use App\Domain\Entity\Measurement;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\EventStorming\MeasurementCreated\MeasurementCreatedEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Infrastructure\Messenger\Event\EventBus;

final readonly class CreateMeasurementHandler implements CommandHandlerInterface
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(CreateMeasurementCommand $command): void
    {
        $measurement = new Measurement(
            parameterId: $command->parameterId,
            deviceId: $command->deviceId,
            value: $command->value,
            recordedAt: new \DateTimeImmutable('now')
        );

        $this->measurementRepository->save($measurement);

        $measurementCreatedEvent = new MeasurementCreatedEvent($measurement->getId());
        $this->eventBus->dispatch($measurementCreatedEvent);
    }
}
