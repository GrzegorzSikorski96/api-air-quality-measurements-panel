<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Entity;

use App\Domain\Entity\Measurement;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

final class MeasurementBuilder
{
    private Uuid $id;
    private float $value;
    private Uuid $measurementParameterId;
    private Uuid $deviceId;
    private \DateTimeImmutable $recordedAt;

    public function withId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function withMeasurementParameterId(Uuid $measurementParameterId): self
    {
        $this->measurementParameterId = $measurementParameterId;

        return $this;
    }

    public function withDeviceId(Uuid $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function withRecordedAt(\DateTimeImmutable $recordedAt): self
    {
        $this->recordedAt = $recordedAt;

        return $this;
    }

    public static function any(): self
    {
        return new MeasurementBuilder();
    }

    public function build(): Measurement
    {
        $faker = Factory::create();

        return new Measurement(
            measurementParameterId: $this->measurementParameterId ?? Uuid::v4(),
            deviceId: $this->deviceId ?? Uuid::v4(),
            value: $this->value ?? $faker->randomFloat(5, 1, 50),
            recordedAt: $this->recordedAt ?? new \DateTimeImmutable(),
            id: $this->id ?? Uuid::v4()
        );
    }
}
