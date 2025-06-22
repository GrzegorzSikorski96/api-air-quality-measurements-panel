<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase;

use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceCommand;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceHandler;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

final class AssignMeasurementParameterToDeviceHandlerTest extends UnitTestCase
{
    private CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(AssignMeasurementParameterToDeviceHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    #[Test]
    public function assignMeasurementParameterToDevice()
    {
        // given
        $givenDevice = DeviceBuilder::any()->withId(
            Uuid::fromString('1ba2e0ec-805c-4d13-a6f3-53f9f560bcd7')
        )->build();
        $this->deviceRepository->save($givenDevice);

        $givenMeasurementParameter = MeasurementParameterBuilder::any()->withId(
            Uuid::fromString('43b3df57-bb72-4499-94fd-5f52253d736d')
        )->build();
        $this->measurementParameterRepository->save($givenMeasurementParameter);

        $givenAssignMeasurementParameterToDeviceCommand = new AssignMeasurementParameterToDeviceCommand(
            measurementParameterId: $givenMeasurementParameter->getId(),
            deviceId: $givenDevice->getId()
        );

        $assignedMeasurementParameter = $this->deviceMeasurementParameterRepository
            ->findOneByDeviceIdAndMeasurementParameterId(
                $givenDevice->getId(),
                $givenMeasurementParameter->getId()
            );
        Assert::assertNull($assignedMeasurementParameter);

        // when
        $this->handler->__invoke($givenAssignMeasurementParameterToDeviceCommand);

        // then
        $assignedMeasurementParameter = $this->deviceMeasurementParameterRepository
            ->findOneByDeviceIdAndMeasurementParameterId(
                $givenDevice->getId(),
                $givenMeasurementParameter->getId()
            );
        Assert::assertNotNull($assignedMeasurementParameter);
    }
}
