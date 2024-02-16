<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use Exception;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;
use App\Tests\Common\UnitTestCase;
use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Fixtures\Entity\DeviceMeasurementParameterBuilder;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;

abstract class DeviceMeasurementParameterRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): DeviceMeasurementParameterRepositoryInterface;
    abstract protected function save(DeviceMeasurementParameter $deviceMeasurementParameter): void;

    /** @test */
    public function save_and_get_device_measurement_parameter(): void
    {
        // given
        $givenDeviceMeasurementParameterId = Uuid::v4();
        $givenDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()
            ->withId($givenDeviceMeasurementParameterId)
            ->build();
        //when
        $this->save($givenDeviceMeasurementParameter);
        $deviceMeasurementParameter = $this->repository()->get($givenDeviceMeasurementParameter->getId());

        //then
        Assert::assertNotNull($givenDeviceMeasurementParameter);
        Assert::assertEquals($givenDeviceMeasurementParameter, $deviceMeasurementParameter);
    }

    /** @test */
    public function throw_exception_when_device_measuremet_parameter_exists(): void
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

        //except
        $this->expectExceptionMessage(sprintf("DETAIL:  Key (device_id, measurement_parameter_id)=(%s, %s) already exists.", $givenDeviceId->toRfc4122(), $givenMeasurementParameterId->toRfc4122()));

        //when
            $this->save($givenSecondDeviceMeasurementParameter);
        try {

        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }

    /** @test */
    public function find_one(): void
    {
        // given
        $givenMeasurementParameterId = Uuid::v4();
        $givenMeasurementParameter = DeviceMeasurementParameterBuilder::any()
            ->withId($givenMeasurementParameterId)
            ->build();
        $this->save($givenMeasurementParameter);

        //when
        $measurementParameter = $this->repository()->findOne($givenMeasurementParameterId);

        //then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    /** @test */
    public function dont_find()
    {
        //given

        //when
        $measurementParameter = $this->repository()->findOne(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));

        //then
        Assert::assertNull($measurementParameter);
    }

    /** @test */
    public function dont_get()
    {
        //expect
        $this->expectException(NonExistentEntityException::class);

        //when
        $this->repository()->get(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));
    }

    /** @test */
    public function find_one_by_device_id_and_measurement_parameter_id()
    {
        //given
        $givenDeviceMeasurementParameter = DeviceMeasurementParameterBuilder::any()->build();
        $this->save($givenDeviceMeasurementParameter);

        //when
        $deviceMeasurementParameter = $this->deviceMeasurementParameterRepository->findOneByDeviceIdAndMeasurementParameterId($givenDeviceMeasurementParameter->getDeviceId(), $givenDeviceMeasurementParameter->getMeasurementParameterId());

        //then
        Assert::assertNotNull($deviceMeasurementParameter);
    }


    /** @test */
    public function dont_find_one_by_device_id_and_measurement_parameter_id()
    {
        //given

        //when
        $deviceMeasurementParameter = $this->deviceMeasurementParameterRepository->findOneByDeviceIdAndMeasurementParameterId(Uuid::v4(), Uuid::v4());

        //then
        Assert::assertNull($deviceMeasurementParameter);
    }
}