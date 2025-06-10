<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UI\Controller\Api\V1;

use App\Tests\Common\AcceptanceTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use App\Tests\Fixtures\Entity\MeasurementBuilder;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class MeasurementControllerTest extends AcceptanceTestCase
{
    /** @test */
    public function measurements()
    {
        // given
        $givenDevice = DeviceBuilder::any()->build();
        $this->handleCreateDevice($givenDevice);

        $givenMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->handleCreateMeasurementParameter($givenMeasurementParameter);

        $givenStartDateTime = new DateTimeImmutable('2024-02-01');

        $givenMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDevice->getId())
        ->withMeasurementParameterId($givenMeasurementParameter->getId())
        ->withValue(13.24)
        ->withRecordedAt(new DateTimeImmutable('2024-02-01 13:00:00'))
        ->build();
        $this->handleCreateMeasurement($givenMeasurement);

        // when
        $response = $this->selfRequest('GET', sprintf(
            '/api/v1/measurements?deviceId=%s&measurementParameterId=%s&startDateTime=%s',
            $givenDevice->getId()->toRfc4122(),
            $givenMeasurementParameter->getId()->toRfc4122(),
            $givenStartDateTime->format('Y-m-d H:i:s')
        ));

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $measurements = json_decode($response->getContent());

        Assert::assertIsArray($measurements);
        Assert::assertIsObject($measurements[0]);

        Assert::assertObjectHasProperty('value', $measurements[0]);
        Assert::assertIsFloat($measurements[0]->value);

        Assert::assertObjectHasProperty('recordedAt', $measurements[0]);
        Assert::assertIsString($measurements[0]->recordedAt);
        Assert::assertTrue(is_numeric(strtotime($measurements[0]->recordedAt)));
    }

    /** @test */
    public function notExistingDevice()
    {
        // given
        $givenNotExistingDeviceId = Uuid::v4();

        $givenOtherDevice = DeviceBuilder::any()->build();
        $this->handleCreateDevice($givenOtherDevice);

        $givenMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->handleCreateMeasurementParameter($givenMeasurementParameter);

        $givenStartDateTime = new DateTimeImmutable('2024-02-01');

        $givenMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenOtherDevice->getId())
        ->withMeasurementParameterId($givenMeasurementParameter->getId())
        ->withValue(13.24)
        ->withRecordedAt(new DateTimeImmutable('2024-02-01 13:00:00'))
        ->build();
        $this->handleCreateMeasurement($givenMeasurement);

        // when
        $response = $this->selfRequest('GET', sprintf(
            '/api/v1/measurements?deviceId=%s&measurementParameterId=%s&startDateTime=%s',
            $givenNotExistingDeviceId->toRfc4122(),
            $givenMeasurementParameter->getId()->toRfc4122(),
            $givenStartDateTime->format('Y-m-d H:i:s')
        ));

        // then
        Assert::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /** @test */
    public function notExistingMeasurementParameter()
    {
        // given
        $givenNotExistingMeasurementParameterId = Uuid::v4();

        $givenDevice = DeviceBuilder::any()->build();
        $this->handleCreateDevice($givenDevice);

        $givenOtherMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->handleCreateMeasurementParameter($givenOtherMeasurementParameter);

        $givenStartDateTime = new DateTimeImmutable('2024-02-01');

        $givenMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDevice->getId())
        ->withMeasurementParameterId($givenOtherMeasurementParameter->getId())
        ->withValue(13.24)
        ->withRecordedAt(new DateTimeImmutable('2024-02-01 13:00:00'))
        ->build();
        $this->handleCreateMeasurement($givenMeasurement);

        // when
        $response = $this->selfRequest('GET', sprintf(
            '/api/v1/measurements?deviceId=%s&measurementParameterId=%s&startDateTime=%s',
            $givenDevice->getId()->toRfc4122(),
            $givenNotExistingMeasurementParameterId->toRfc4122(),
            $givenStartDateTime->format('Y-m-d H:i:s')
        ));

        // then
        Assert::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
