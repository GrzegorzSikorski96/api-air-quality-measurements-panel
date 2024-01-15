<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase;

use App\Domain\Entity\Enum\ApiProviderEnum;
use App\Infrastructure\Messenger\CommandHandlerInterface;
use App\Tests\Common\UnitTestCase;
use App\UseCase\CreateDevice\CreateDeviceCommand;
use App\UseCase\CreateDevice\CreateDeviceHandler;
use PHPUnit\Framework\Assert;

final class CreateDeviceHandlerTest extends UnitTestCase
{
    private CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(CreateDeviceHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    /** @test */
    public function create_measurement_parameter()
    {
        // given
        $givenName = 'Given name';
        $givenLatitude = 51.1270456;
        $givenLongitude = 16.6622347;
        $givenProvider = ApiProviderEnum::LOOKO2->value;
        $givenExternalId = 'externalId';
        $givenToken = 'sdbsduhfbdjbdfhgbh';

        $givenCreateDeviceCommand = new CreateDeviceCommand(
            name: $givenName,
            latitude: $givenLatitude,
            longitude: $givenLongitude,
            apiProvider: $givenProvider,
            externalId: $givenExternalId,
            token: $givenToken
        );

        $devices = $this->deviceRepository->findAll();
        Assert::assertCount(0, $devices);

        //when
        $this->handler->__invoke($givenCreateDeviceCommand);

        //then
        $devices = $this->deviceRepository->findAll();
        Assert::assertCount(1, $devices);
    }
}