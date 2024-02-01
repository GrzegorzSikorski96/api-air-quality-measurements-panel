<?php

declare(strict_types=1);

namespace App\UseCase\CreateDevice;

use App\Domain\Entity\Device;
use App\Domain\Entity\Enum\ApiProviderEnum;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\EventStorming\DeviceCreated\DeviceCreatedEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Infrastructure\Messenger\Event\EventBus;

final readonly class CreateDeviceHandler implements CommandHandlerInterface
{
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(CreateDeviceCommand $command): void
    {
        $device = new Device(
            name: $command->name,
            latitude: $command->latitude,
            longitude: $command->longitude,
            provider: ApiProviderEnum::from($command->apiProvider),
            externalId: $command->externalId,
            token: $command->token,
            id: $command->id,
        );

        $this->deviceRepository->save($device);

        $deviceCreatedEvent = new DeviceCreatedEvent($device->getId());
        $this->eventBus->dispatch($deviceCreatedEvent);
    }
}
