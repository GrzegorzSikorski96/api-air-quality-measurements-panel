<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UseCase;

use App\EventStorming\MeasurementParameterAssignedToDevice\MeasurementParameterAssignedToDeviceEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Tests\Common\AcceptanceTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceCommand;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceHandler;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class AssignMeasurementParameterToDeviceHandlerTest extends AcceptanceTestCase
{
    protected CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(AssignMeasurementParameterToDeviceHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    /** @test */
    public function assignMeasurementParameterToDeviceHandlerTest(): void
    {
        // given
        $givenDevice = DeviceBuilder::any()
            ->withId(Uuid::v4())
            ->build();
        $this->handleCreateDevice($givenDevice);

        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withId(Uuid::v4())
            ->build();
        $this->handleCreateMeasurementParameter($givenMeasurementParameter);

        $givenAssignMeasurementParameterToDevice = new AssignMeasurementParameterToDeviceCommand(
            measurementParameterId: $givenMeasurementParameter->getId(),
            deviceId: $givenDevice->getId(),
        );

        // when
        $this->commandBus->dispatch($givenAssignMeasurementParameterToDevice);
        $this->asyncTransport->process(1);

        // then
        $this->asyncTransport->dispatched()->assertCount(2);
        $this->asyncTransport->dispatched()->assertContains(AssignMeasurementParameterToDeviceCommand::class, 1);
        $this->asyncTransport->dispatched()->assertContains(MeasurementParameterAssignedToDeviceEvent::class, 1);
    }
}
