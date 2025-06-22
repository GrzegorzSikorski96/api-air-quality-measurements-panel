<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

abstract class MeasurementParameterRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): MeasurementParameterRepositoryInterface;

    abstract protected function save(MeasurementParameter $measurementParameter): void;

    #[Test]
    public function saveAndGetMeasurementParameter(): void
    {
        // given
        $givenId = Uuid::fromString('f675e192-dad9-4ae8-af69-ecd80e870fa7');
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withId($givenId)
            ->build();

        // when
        $this->save($givenMeasurementParameter);
        $measurementParameter = $this->repository()->get($givenId);

        // then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    #[Test]
    public function throwExceptionIfDuplicatedName(): void
    {
        // given
        $givenDuplicatedField = 'name';
        $givenDuplicatedValue = 'Duplicated name';

        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName($givenDuplicatedValue)
            ->build();
        $this->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName($givenDuplicatedValue)
            ->build();

        // expect
        $this->expectExceptionMessageMatches(
            sprintf(
                "/DETAIL:  Key \(%s\)=\(%s\) already exists\./i",
                $givenDuplicatedField,
                $givenDuplicatedValue
            )
        );

        // when
        $this->save($givenSecondMeasurementParameter);
    }

    #[Test]
    public function throwExceptionIfDuplicatedCode(): void
    {
        // given
        $givenDuplicatedField = 'code';
        $givenDuplicatedValue = 'code';

        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()
            ->withCode($givenDuplicatedValue)
            ->build();
        $this->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()
            ->withCode($givenDuplicatedValue)
            ->build();

        // expect
        $this->expectExceptionMessageMatches(
            sprintf(
                "/DETAIL:  Key \(%s\)=\(%s\) already exists\./i",
                $givenDuplicatedField,
                $givenDuplicatedValue
            )
        );

        // when
        $this->save($givenSecondMeasurementParameter);
    }

    #[Test]
    public function throwExceptionIfDuplicatedFormula(): void
    {
        // given
        $givenDuplicatedField = 'formula';
        $givenDuplicatedValue = 'Duplicated formula';

        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()
            ->withFormula($givenDuplicatedValue)
            ->build();
        $this->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()
            ->withFormula($givenDuplicatedValue)
            ->build();

        // expect
        $this->expectExceptionMessageMatches(
            sprintf(
                "/DETAIL:  Key \(%s\)=\(%s\) already exists\./i",
                $givenDuplicatedField,
                $givenDuplicatedValue
            )
        );

        // when
        $this->save($givenSecondMeasurementParameter);
    }

    #[Test]
    public function findOne(): void
    {
        // given
        $givenId = Uuid::fromString('f675e192-dad9-4ae8-af69-ecd80e870fa7');
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withId($givenId)
            ->build();

        // when
        $this->save($givenMeasurementParameter);
        $measurementParameter = $this->repository()->findOne($givenId);

        // then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    #[Test]
    public function dontFindOne()
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
    public function findOneByName(): void
    {
        // given
        $givenName = 'pył zawieszony PM10';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName($givenName)
            ->build();

        // when
        $this->save($givenMeasurementParameter);
        $measurementParameter = $this->repository()->findOneByName($givenName);

        // then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    #[Test]
    public function dontFindOneByName(): void
    {
        // given
        $givenNotExistingName = 'pył zawieszony PM10';

        // when
        $measurementParameter = $this->repository()->findOneByName($givenNotExistingName);

        // then
        Assert::assertNull($measurementParameter);
    }

    #[Test]
    public function findOneByCode(): void
    {
        // given
        $givenCode = 'PM10';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withCode($givenCode)
            ->build();

        // when
        $this->save($givenMeasurementParameter);
        $measurementParameter = $this->repository()->findOneByCode($givenCode);

        // then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    #[Test]
    public function dontFindOneByCode(): void
    {
        // given
        $givenNotExistingCode = 'PM10';

        // when
        $measurementParameter = $this->repository()->findOneByCode($givenNotExistingCode);

        // then
        Assert::assertNull($measurementParameter);
    }

    #[Test]
    public function findOneByFormula(): void
    {
        // given
        $givenFormula = 'PM10';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withFormula($givenFormula)
            ->build();

        // when
        $this->save($givenMeasurementParameter);
        $measurementParameter = $this->repository()->findOneByFormula($givenFormula);

        // then
        Assert::assertNotNull($measurementParameter);
        Assert::assertEquals($givenMeasurementParameter, $measurementParameter);
    }

    #[Test]
    public function dontFindOneByFormula(): void
    {
        // given
        $givenNotExistingFormula = 'PM10';

        // when
        $measurementParameter = $this->repository()->findOneByFormula($givenNotExistingFormula);

        // then
        Assert::assertNull($measurementParameter);
    }

    #[Test]
    public function findAll()
    {
        // given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->save($givenSecondMeasurementParameter);

        $givenThirdMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->save($givenThirdMeasurementParameter);

        // when
        $allMeasurementParameters = $this->repository()->findAll();

        // then
        Assert::assertCount(3, $allMeasurementParameters);
        Assert::assertContainsOnlyInstancesOf(MeasurementParameter::class, $allMeasurementParameters);
    }

    #[Test]
    public function dontGet()
    {
        // expect
        $this->expectException(NonExistentEntityException::class);

        // when
        $this->repository()->get(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));
    }
}
