<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Fixtures\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

abstract class MeasurementParameterRepositoryTestTemplate extends KernelTestCase
{
    abstract protected function repository(): MeasurementParameterRepositoryInterface;

    abstract protected function save(MeasurementParameter $measurementParameter): void;

    /** @test */
    public function add_and_get_measurement_parameter(): void
    {
        // given
        $givenId = Uuid::fromString('f675e192-dad9-4ae8-af69-ecd80e870fa7');
        $givenName = "pył zawieszony PM10";
        $givenFormula = "PM10";
        $givenCode = "PM10";
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName($givenName)
            ->withCode($givenCode)
            ->withFormula($givenFormula)
            ->withId($givenId)
            ->build();

        //when
        $this->save($givenMeasurementParameter);
        $measurementParameter = $this->repository()->get($givenMeasurementParameter->getId());

        //then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    /** @test */
    public function find_by_id(): void
    {
        // given
        $givenId = Uuid::fromString('f675e192-dad9-4ae8-af69-ecd80e870fa7');
        $givenName = "pył zawieszony PM10";
        $givenFormula = "PM10";
        $givenCode = "PM10";
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName($givenName)
            ->withCode($givenCode)
            ->withFormula($givenFormula)
            ->withId($givenId)
            ->build();

        //when
        $this->save($givenMeasurementParameter);
        $measurementParameter = $this->repository()->findOne($givenId);

        //then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    /** @test */
    public function find_all()
    {
        //given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName('parameter_1')
            ->withCode('PARAMETER1')
            ->withFormula('PA1')
            ->build();
        $this->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName('parameter_2')
            ->withCode('PARAMETER2')
            ->withFormula('PA2')
            ->build();
        $this->save($givenSecondMeasurementParameter);

        $givenThirdMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName('parameter_3')
            ->withCode('PARAMETER3')
            ->withFormula('PA3')
            ->build();
        $this->save($givenThirdMeasurementParameter);

        //when
        $allMeasurementParameters = $this->repository()->findAll();

        //then
        Assert::assertCount(3, $allMeasurementParameters);
        Assert::assertContainsOnlyInstancesOf(MeasurementParameter::class, $allMeasurementParameters);
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
}