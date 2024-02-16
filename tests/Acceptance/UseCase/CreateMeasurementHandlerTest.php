<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UseCase;

use App\EventStorming\MeasurementCreated\MeasurementCreatedEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Tests\Common\AcceptanceTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use App\Tests\Fixtures\Entity\DeviceMeasurementParameterBuilder;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceCommand;
use App\UseCase\CreateMeasurement\CreateMeasurementCommand;
use App\UseCase\CreateMeasurement\CreateMeasurementHandler;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class CreateMeasurementHandlerTest extends AcceptanceTestCase
{
    protected CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(CreateMeasurementHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    /** @test */
    public function create_measurement_handler_test(): void
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

        $givenCreateDeviceCommand = new CreateMeasurementCommand(
            parameterId: $givenMeasurementParameter->getId(),
            deviceId: $givenDevice->getId(),
            value: 13.2,
            recordedAt: new DateTimeImmutable('now')
        );

        // when
        $this->commandBus->dispatch($givenCreateDeviceCommand);
        $this->asyncTransport->process(1);

        // then
        $this->asyncTransport->dispatched()->assertCount(2);
        $this->asyncTransport->dispatched()->assertContains(CreateMeasurementCommand::class, 1);
        $this->asyncTransport->dispatched()->assertContains(MeasurementCreatedEvent::class, 1);
    }
    
    /** @test */
    public function create_measurement_and_dispatch_assign_measurement_parameter_to_device_test(): void
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

        $givenCreateMeasurementCommand = new CreateMeasurementCommand(
            parameterId: $givenMeasurementParameter->getId(),
            deviceId: $givenDevice->getId(),
            value: 13.2,
            recordedAt: new DateTimeImmutable('now')
        );

        // when
        $this->commandBus->dispatch($givenCreateMeasurementCommand);
        $this->asyncTransport->process(2);

        // then
        $this->asyncTransport->dispatched()->assertCount(3);
        $this->asyncTransport->dispatched()->assertContains(CreateMeasurementCommand::class, 1);
        $this->asyncTransport->dispatched()->assertContains(MeasurementCreatedEvent::class, 1);
        $this->asyncTransport->dispatched()->assertContains(AssignMeasurementParameterToDeviceCommand::class, 1);
    }
    
    /** @test */
    public function create_measurement_and_dont_dispatch_assign_measurement_parameter_to_device_test(): void
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

        $givenDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()
        ->withDeviceId($givenDevice->getId())
        ->withMeasurementParameterId($givenMeasurementParameter->getId())
        ->build();
        $this->handleAssignMeasurementParameterToDevice($givenDeviceMeasurementParameter);

        $givenCreateMeasurementCommand = new CreateMeasurementCommand(
            parameterId: $givenMeasurementParameter->getId(),
            deviceId: $givenDevice->getId(),
            value: 13.2,
            recordedAt: new DateTimeImmutable('now')
        );

        // when
        $this->commandBus->dispatch($givenCreateMeasurementCommand);
        $this->asyncTransport->process(2);

        // then
        $this->asyncTransport->dispatched()->assertCount(2);
        $this->asyncTransport->dispatched()->assertContains(CreateMeasurementCommand::class, 1);
        $this->asyncTransport->dispatched()->assertContains(MeasurementCreatedEvent::class, 1);
        $this->asyncTransport->dispatched()->assertNotContains(AssignMeasurementParameterToDeviceCommand::class, 1);
    }
}
