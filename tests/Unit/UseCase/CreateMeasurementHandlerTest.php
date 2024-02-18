<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase;

use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Tests\Common\UnitTestCase;
use App\UseCase\CreateMeasurement\CreateMeasurementCommand;
use App\UseCase\CreateMeasurement\CreateMeasurementHandler;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class CreateMeasurementHandlerTest extends UnitTestCase
{
    private CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(CreateMeasurementHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    /** @test */
    public function createMeasurementParameter()
    {
        // given
        $givenParameterId = Uuid::v4();
        $givenDeviceId = Uuid::v4();
        $givenValue = 12.2;
        $givenRecordedAt = new \DateTimeImmutable('2024-01-19 22:45:00');

        $givenCreateMeasurementCommand = new CreateMeasurementCommand(
            measurementParameterId: $givenParameterId,
            deviceId: $givenDeviceId,
            value: $givenValue,
            recordedAt: $givenRecordedAt
        );

        $measurements = $this->measurementRepository->findAll();
        Assert::assertCount(0, $measurements);

        // when
        $this->handler->__invoke($givenCreateMeasurementCommand);

        // then
        $measurements = $this->measurementRepository->findAll();
        Assert::assertCount(1, $measurements);
    }
}
