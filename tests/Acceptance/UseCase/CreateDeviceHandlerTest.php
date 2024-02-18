<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UseCase;

use App\Domain\Entity\Enum\ApiProviderEnum;
use App\EventStorming\DeviceCreated\DeviceCreatedEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Tests\Common\AcceptanceTestCase;
use App\UseCase\CreateDevice\CreateDeviceCommand;
use App\UseCase\CreateDevice\CreateDeviceHandler;
use PHPUnit\Framework\Assert;

final class CreateDeviceHandlerTest extends AcceptanceTestCase
{
    protected CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(CreateDeviceHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    /** @test */
    public function createDeviceHandlerTest(): void
    {
        // given
        $givenCreateDeviceCommand = new CreateDeviceCommand(
            name: 'Given device name',
            latitude: 23,
            longitude: 23,
            apiProvider: ApiProviderEnum::LOOKO2->value
        );

        // when
        $this->commandBus->dispatch($givenCreateDeviceCommand);
        $this->asyncTransport->process(1);

        // then
        $this->asyncTransport->dispatched()->assertCount(2);
        $this->asyncTransport->dispatched()->assertContains(CreateDeviceCommand::class, 1);
        $this->asyncTransport->dispatched()->assertContains(DeviceCreatedEvent::class, 1);
    }
}
