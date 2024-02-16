<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller;

use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use Symfony\Component\HttpFoundation\Response;

final class DeviceControllerTest extends UnitTestCase
{    
    /** @test */
    public function all_devices()
    {
        // given
        $givenFirstDevice = DeviceBuilder::any()->build();
        $this->deviceRepository->save($givenFirstDevice);

        $givenSecondDevice = DeviceBuilder::any()->build();
        $this->deviceRepository->save($givenSecondDevice);

        // when
        $response = $this->selfRequest('GET', '/devices');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $devices = json_decode($response->getContent());

        foreach($devices as $device) {
            if ($device->id === $givenFirstDevice->getId()->toRfc4122()) {
                $expectedDevice = $givenFirstDevice;
            } else if ($device->id === $givenSecondDevice->getId()->toRfc4122()) {
                $expectedDevice = $givenSecondDevice;}

            Assert::assertEquals($expectedDevice->getId(), $device->id);
            Assert::assertEquals($expectedDevice->getName(), $device->name);
            Assert::assertEquals($expectedDevice->getLatitude(), $device->latitude);
            Assert::assertEquals($expectedDevice->getLongitude(), $device->longitude);
            Assert::assertEquals($expectedDevice->getProvider()->value, $device->provider);
        }
    }
    
    /** @test */
    public function existing_device()
    {
        // given
        $givenFirstDevice = DeviceBuilder::any()
        ->withId(Uuid::fromString('43192d2a-724e-4e43-b5bd-ec0588b38c53'))
        ->build();
        $this->deviceRepository->save($givenFirstDevice);

        $givenSecondDevice = DeviceBuilder::any()
        ->withId(Uuid::v4())
        ->build();
        $this->deviceRepository->save($givenSecondDevice);

        // when
        $response = $this->selfRequest('GET', '/device/43192d2a-724e-4e43-b5bd-ec0588b38c53');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $device = json_decode($response->getContent());

        Assert::assertEquals($givenFirstDevice->getId(), $device->id);
        Assert::assertEquals($givenFirstDevice->getName(), $device->name);
        Assert::assertEquals($givenFirstDevice->getLatitude(), $device->latitude);
        Assert::assertEquals($givenFirstDevice->getLongitude(), $device->longitude);
        Assert::assertEquals($givenFirstDevice->getProvider()->value, $device->provider);
    }
    
    /** @test */
    public function not_existing_device()
    {
        // given

        // when
        $response = $this->selfRequest('GET', '/device/43192d2a-724e-4e43-b5bd-ec0588b38c53');

        // then
        Assert::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}