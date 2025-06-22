<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\DeviceMeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

abstract class DeviceMeasurementParameterRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): DeviceMeasurementParameterRepositoryInterface;

    abstract protected function save(DeviceMeasurementParameter $deviceMeasurementParameter): void;

    #[Test]
    public function saveAndGetDeviceMeasurementParameter(): void
    {
        // given
        $givenDeviceMeasurementParameterId = Uuid::v4();
        $givenDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()
            ->withId($givenDeviceMeasurementParameterId)
            ->build();
        // when
        $this->save($givenDeviceMeasurementParameter);
        $deviceMeasurementParameter = $this->repository()->get($givenDeviceMeasurementParameter->getId());

        // then
        Assert::assertNotNull($givenDeviceMeasurementParameter);
        Assert::assertEquals($givenDeviceMeasurementParameter, $deviceMeasurementParameter);
    }

    #[Test]
    public function throwExceptionWhenDeviceMeasurementParameterExists(): void
    {
        // given
        $givenDeviceId = Uuid::v4();
        $givenMeasurementParameterId = Uuid::v4();

        $givenFirstDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()
            ->withDeviceId($givenDeviceId)
            ->withMeasurementParameterId($givenMeasurementParameterId)
            ->build();
        $this->save($givenFirstDeviceMeasurementParameter);

        $givenSecondDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()
            ->withDeviceId($givenDeviceId)
            ->withMeasurementParameterId($givenMeasurementParameterId)
            ->build();

        // except
        $this->expectExceptionMessage(
            sprintf(
                'DETAIL:  Key (device_id, measurement_parameter_id)=(%s, %s) already exists.',
                $givenDeviceId->toRfc4122(),
                $givenMeasurementParameterId->toRfc4122()
            )
        );

        // when
        $this->save($givenSecondDeviceMeasurementParameter);
    }

    #[Test]
    public function findOne(): void
    {
        // given
        $givenMeasurementParameterId = Uuid::v4();
        $givenMeasurementParameter = DeviceMeasurementParameterBuilder::any()
            ->withId($givenMeasurementParameterId)
            ->build();
        $this->save($givenMeasurementParameter);

        // when
        $measurementParameter = $this->repository()->findOne($givenMeasurementParameterId);

        // then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    #[Test]
    public function dontFind()
    {
        // given

        // when
        $measurementParameter = $this->repository()->findOne(
            Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc')
        );

        // then
        Assert::assertNull($measurementParameter);
    }

    #[Test]
    public function dontGet()
    {
        // expect
        $this->expectException(NonExistentEntityException::class);

        // when
        $this->repository()->get(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));
    }

    #[Test]
    public function findOneByDeviceIdAndMeasurementParameterId()
    {
        // given
        $givenDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()->build();
        $this->save($givenDeviceMeasurementParameter);

        // when
        $deviceMeasurementParameter = $this->deviceMeasurementParameterRepository
            ->findOneByDeviceIdAndMeasurementParameterId(
                $givenDeviceMeasurementParameter->getDeviceId(),
                $givenDeviceMeasurementParameter->getMeasurementParameterId()
            );

        // then
        Assert::assertNotNull($deviceMeasurementParameter);
    }

    #[Test]
    public function dontFindOneByDeviceIdAndMeasurementParameterId()
    {
        // given

        // when
        $deviceMeasurementParameter = $this->deviceMeasurementParameterRepository
            ->findOneByDeviceIdAndMeasurementParameterId(
                Uuid::v4(),
                Uuid::v4()
            );

        // then
        Assert::assertNull($deviceMeasurementParameter);
    }
}
