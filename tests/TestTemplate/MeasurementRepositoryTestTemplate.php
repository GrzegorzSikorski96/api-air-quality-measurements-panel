<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\Measurement;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\MeasurementBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

abstract class MeasurementRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): MeasurementRepositoryInterface;
    abstract protected function save(Measurement $measurement): void;

    /** @test */
    public function save_and_get_measurement(): void
    {
        // given
        $givenParameterId = Uuid::v4();
        $givenRecordedAt = new DateTimeImmutable('2024-01-01 00:00:00');
        $givenValue = 13.5234;
        $givenId = Uuid::fromString('f675e192-dad9-4ae8-af69-ecd80e870fa7');
        $givenMeasurement = MeasurementBuilder::any()
            ->withId($givenId)
            ->withParameterId($givenParameterId)
            ->withRecordedAt($givenRecordedAt)
            ->withValue($givenValue)
            ->build();
        //when
        $this->save($givenMeasurement);
        $measurement = $this->repository()->get($givenMeasurement->getId());

        //then
        Assert::assertNotNull($measurement);
        Assert::assertEquals($givenMeasurement, $measurement);
    }

    /** @test */
    public function find_one(): void
    {
        // given
        $givenParameterId = Uuid::v4();
        $givenRecordedAt = new DateTimeImmutable('2024-01-01 00:00:00');
        $givenValue = 13.5234;
        $givenId = Uuid::fromString('f675e192-dad9-4ae8-af69-ecd80e870fa7');
        $givenMeasurement = MeasurementBuilder::any()
            ->withId($givenId)
            ->withParameterId($givenParameterId)
            ->withRecordedAt($givenRecordedAt)
            ->withValue($givenValue)
            ->build();

        //when
        $this->save($givenMeasurement);
        $measurement = $this->repository()->findOne($givenId);

        //then
        Assert::assertNotNull($measurement);
        Assert::assertEquals($givenMeasurement, $measurement);
    }

    /** @test */
    public function find_all()
    {
        //given
        $givenFirstMeasurement = MeasurementBuilder::any()->build();
        $this->save($givenFirstMeasurement);

        $givenSecondMeasurement = MeasurementBuilder::any()->build();
        $this->save($givenSecondMeasurement);

        $givenThirdMeasurement = MeasurementBuilder::any()->build();
        $this->save($givenThirdMeasurement);

        //when
        $allMeasurements = $this->repository()->findAll();

        //then
        Assert::assertCount(3, $allMeasurements);
        Assert::assertContainsOnlyInstancesOf(Measurement::class, $allMeasurements);
    }

    /** @test */
    public function dont_find()
    {
        //given

        //when
        $measurement = $this->repository()->findOne(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));

        //then
        Assert::assertNull($measurement);
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