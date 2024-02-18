<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Api\V1;

use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use App\Tests\Fixtures\Entity\MeasurementBuilder;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class MeasurementControllerTest extends UnitTestCase
{
    /** @test */
    public function measurements()
    {
        // given
        $givenDevice = DeviceBuilder::any()->build();
        $this->deviceRepository->save($givenDevice);

        $givenMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->measurementParameterRepository->save($givenMeasurementParameter);

        $givenStartDateTime = new \DateTimeImmutable('2024-02-01');

        $givenMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDevice->getId())
        ->withParameterId($givenMeasurementParameter->getId())
        ->withValue(13.24)
        ->withRecordedAt(new \DateTimeImmutable('2024-02-01 13:00:00'))
        ->build();
        $this->measurementRepository->save($givenMeasurement);

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
        Assert::assertEquals(13.24, $measurements[0]->value);
        Assert::assertEquals('2024-02-01 13:00:00', $measurements[0]->recordedAt);
    }

    /** @test */
    public function notExistingDevice()
    {
        // given
        $givenNotExistingDeviceId = Uuid::v4();

        $givenMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->measurementParameterRepository->save($givenMeasurementParameter);

        $givenStartDateTime = new \DateTime('2024-02-01');

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
        $givenDevice = DeviceBuilder::any()->build();
        $this->deviceRepository->save($givenDevice);

        $givenNotExistingMeasurementParameterId = Uuid::v4();
        $givenStartDateTime = new \DateTime('2024-02-01');

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
