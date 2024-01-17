<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UseCase;

use App\Domain\Entity\Enum\ApiProviderEnum;
use App\EventStorming\DeviceCreated\DeviceCreatedEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Tests\Asserts\InMemoryTransportAssert;
use App\Tests\Common\UnitTestCase;
use App\UseCase\CreateDevice\CreateDeviceCommand;
use App\UseCase\CreateDevice\CreateDeviceHandler;
use PHPUnit\Framework\Assert;

final class CreateDeviceHandlerTest extends UnitTestCase
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
    public function create_device_handler_test(): void
    {
        // given
        $givenCreateDeviceCommand = new CreateDeviceCommand(
            name: 'Given device name',
            latitude: 23,
            longitude: 23,
            apiProvider: ApiProviderEnum::LOOKO2->value
        );

        // when
        $this->handler->__invoke($givenCreateDeviceCommand);

        // then
        InMemoryTransportAssert::assertMessageOfTypeWasSent(DeviceCreatedEvent::class, $this->asyncTransport);
        InMemoryTransportAssert::assertCountSentMessages(1, $this->asyncTransport);
    }
}
