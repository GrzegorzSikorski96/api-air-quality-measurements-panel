<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller;

use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use App\Tests\Fixtures\Entity\DeviceMeasurementParameterBuilder;

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
    public function device_details()
    {
        // given
        $givenDevice = DeviceBuilder::any()
        ->withId(Uuid::fromString('43192d2a-724e-4e43-b5bd-ec0588b38c53'))
        ->build();
        $this->deviceRepository->save($givenDevice);

        $givenMeasurementParameter = MeasurementParameterBuilder::any()
        ->withId(Uuid::v4())
        ->build();
        $this->measurementParameterRepository->save($givenMeasurementParameter);

        $givenDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()
        ->withDeviceId($givenDevice->getId())
        ->withMeasurementParameterId($givenMeasurementParameter->getId())
        ->build();
        $this->deviceMeasurementParameterRepository->save($givenDeviceMeasurementParameter);

        // when
        $response = $this->selfRequest('GET', '/device/43192d2a-724e-4e43-b5bd-ec0588b38c53/details');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $deviceDetails = json_decode($response->getContent());

        Assert::assertEquals($givenDevice->getId(), $deviceDetails->id);
        Assert::assertEquals($givenDevice->getName(), $deviceDetails->name);
        Assert::assertEquals($givenDevice->getLatitude(), $deviceDetails->latitude);
        Assert::assertEquals($givenDevice->getLongitude(), $deviceDetails->longitude);
        Assert::assertEquals($givenDevice->getProvider()->value, $deviceDetails->provider);

        Assert::assertIsArray($deviceDetails->measurementParameters);

        Assert::assertEquals($givenMeasurementParameter->getId(), $deviceDetails->measurementParameters[0]->id);
        Assert::assertEquals($givenMeasurementParameter->getName(), $deviceDetails->measurementParameters[0]->name);
        Assert::assertEquals($givenMeasurementParameter->getCode(), $deviceDetails->measurementParameters[0]->code);
        Assert::assertEquals($givenMeasurementParameter->getFormula(), $deviceDetails->measurementParameters[0]->formula);
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