<?php

declare(strict_types=1);

namespace App\UseCase\CreateMeasurementParameter;

use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\EventStorming\MeasurementParameterCreated\MeasurementParameterCreatedEvent;
use App\Infrastructure\Messenger\CommandHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CreateMeasurementParameterHandler implements CommandHandlerInterface
{
    public function __construct(
        private MeasurementParameterRepositoryInterface $measurementParameterRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CreateMeasurementParameterCommand $command): void
    {
        $measurementParameter = new MeasurementParameter(
            $command->name,
            $command->code,
            $command->formula
        );

        $this->measurementParameterRepository->save($measurementParameter);

        $measurementParameterCreatedEvent = new MeasurementParameterCreatedEvent($measurementParameter->getId());
        $this->eventBus->dispatch($measurementParameterCreatedEvent);
    }
}