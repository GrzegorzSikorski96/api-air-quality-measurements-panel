<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase;

use App\Infrastructure\Messenger\CommandHandlerInterface;
use App\Tests\Common\UnitTestCase;
use App\UseCase\CreateMeasurementParameter\CreateMeasurementParameterCommand;
use App\UseCase\CreateMeasurementParameter\CreateMeasurementParameterHandler;
use PHPUnit\Framework\Assert;

final class CreateMeasurementParameterHandlerTest extends UnitTestCase
{
    private CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(CreateMeasurementParameterHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    /** @test */
    public function create_measurement_parameter()
    {
        // given
        $givenName = 'Given name';
        $givenCode = 'Given code';
        $givenFormula = 'Given formula';

        $givenCreateMeasurementParameterCommand = new CreateMeasurementParameterCommand(
            name: $givenName,
            code: $givenCode,
            formula: $givenFormula
        );

        $measurementParameters = $this->measurementParameterRepository->findAll();
        Assert::assertCount(0, $measurementParameters);

        //when
        $this->handler->__invoke($givenCreateMeasurementParameterCommand);

        //then
        $measurementParameters = $this->measurementParameterRepository->findAll();
        Assert::assertCount(1, $measurementParameters);
    }
}