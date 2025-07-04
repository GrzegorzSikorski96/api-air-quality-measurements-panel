<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\Measurement;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\MeasurementBuilder;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

abstract class MeasurementRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): MeasurementRepositoryInterface;

    abstract protected function save(Measurement $measurement): void;

    #[Test]
    public function saveAndGetMeasurement(): void
    {
        // given
        $givenMeasurementId = Uuid::v4();
        $givenRecordedAt = new DateTimeImmutable('2024-01-01 13:00:00');
        $givenMeasurement = MeasurementBuilder::any()
            ->withId($givenMeasurementId)
            ->withRecordedAt($givenRecordedAt)
            ->build();

        // when
        $this->save($givenMeasurement);
        $measurement = $this->repository()->get($givenMeasurementId);

        // then
        Assert::assertNotNull($measurement);
        Assert::assertEquals($givenMeasurement, $measurement);
    }

    #[Test]
    public function findOne(): void
    {
        // given
        $givenId = Uuid::fromString('f675e192-dad9-4ae8-af69-ecd80e870fa7');
        $givenRecordedAt = new DateTimeImmutable('2024-01-01 13:00:00');
        $givenMeasurement = MeasurementBuilder::any()
            ->withId($givenId)
            ->withRecordedAt($givenRecordedAt)
            ->build();

        // when
        $this->save($givenMeasurement);
        $measurement = $this->repository()->findOne($givenId);

        // then
        Assert::assertNotNull($measurement);
        Assert::assertEquals($givenMeasurement, $measurement);
    }

    #[Test]
    public function findAll()
    {
        // given
        $givenFirstMeasurement = MeasurementBuilder::any()->build();
        $this->save($givenFirstMeasurement);

        $givenSecondMeasurement = MeasurementBuilder::any()->build();
        $this->save($givenSecondMeasurement);

        $givenThirdMeasurement = MeasurementBuilder::any()->build();
        $this->save($givenThirdMeasurement);

        // when
        $allMeasurements = $this->repository()->findAll();

        // then
        Assert::assertCount(3, $allMeasurements);
        Assert::assertContainsOnlyInstancesOf(Measurement::class, $allMeasurements);
    }

    #[Test]
    public function findByDeviceAndStartDateTime()
    {
        // given
        $givenDeviceId = Uuid::v4();
        $givenMeasurementParameterId = Uuid::v4();
        $givenStartDateTime = new DateTime('2024-02-17 12:30:00');

        $givenFirstMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDeviceId)
        ->withMeasurementParameterId($givenMeasurementParameterId)
        ->withRecordedAt(new DateTimeImmutable('2024-02-17 12:00:00'))
        ->build();
        $this->save($givenFirstMeasurement);

        $givenSecondMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDeviceId)
        ->withMeasurementParameterId($givenMeasurementParameterId)
        ->withRecordedAt(new DateTimeImmutable('2024-02-17 13:00:00'))
        ->build();
        $this->save($givenSecondMeasurement);

        $givenThirdMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDeviceId)
        ->withMeasurementParameterId($givenMeasurementParameterId)
        ->withRecordedAt(new DateTimeImmutable('2024-02-17 14:00:00'))
        ->build();
        $this->save($givenThirdMeasurement);

        // when
        $filteredMeasurements = $this->repository()->findByDeviceAndParameterInTimeRange(
            $givenDeviceId,
            $givenMeasurementParameterId,
            $givenStartDateTime
        );

        // then
        Assert::assertCount(2, $filteredMeasurements);
        Assert::assertContainsOnlyInstancesOf(Measurement::class, $filteredMeasurements);
        Assert::assertEquals($filteredMeasurements[0], $givenSecondMeasurement);
        Assert::assertEquals($filteredMeasurements[1], $givenThirdMeasurement);
    }

    #[Test]
    public function findByDeviceAndTimeRange()
    {
        // given
        $givenDeviceId = Uuid::v4();
        $givenMeasurementParameterId = Uuid::v4();
        $givenStartDateTime = new DateTime('2024-02-17 12:30:00');
        $givenEndDateTime = new DateTime('2024-02-17 13:30:00');

        $givenFirstMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDeviceId)
        ->withMeasurementParameterId($givenMeasurementParameterId)
        ->withRecordedAt(new DateTimeImmutable('2024-02-17 12:00:00'))
        ->build();
        $this->save($givenFirstMeasurement);

        $givenSecondMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDeviceId)
        ->withMeasurementParameterId($givenMeasurementParameterId)
        ->withRecordedAt(new DateTimeImmutable('2024-02-17 13:00:00'))
        ->build();
        $this->save($givenSecondMeasurement);

        $givenThirdMeasurement = MeasurementBuilder::any()
        ->withDeviceId($givenDeviceId)
        ->withMeasurementParameterId($givenMeasurementParameterId)
        ->withRecordedAt(new DateTimeImmutable('2024-02-17 14:00:00'))
        ->build();
        $this->save($givenThirdMeasurement);

        // when
        $filteredMeasurements = $this->repository()->findByDeviceAndParameterInTimeRange(
            $givenDeviceId,
            $givenMeasurementParameterId,
            $givenStartDateTime,
            $givenEndDateTime
        );

        // then
        Assert::assertCount(1, $filteredMeasurements);
        Assert::assertContainsOnlyInstancesOf(Measurement::class, $filteredMeasurements);
        Assert::assertEquals($filteredMeasurements[0], $givenSecondMeasurement);
    }

    #[Test]
    public function dontFind()
    {
        // given

        // when
        $measurement = $this->repository()->findOne(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));

        // then
        Assert::assertNull($measurement);
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
