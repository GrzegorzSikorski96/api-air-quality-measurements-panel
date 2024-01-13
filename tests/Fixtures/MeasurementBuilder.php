<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Domain\Entity\Measurement;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Faker\Factory;

final class MeasurementBuilder
{
    private Uuid $id;
    private float $value;
    private Uuid $parameterId;
    private DateTimeImmutable $recordedAt;

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

    public function withParameterId(Uuid $parameterId): self
    {
        $this->parameterId = $parameterId;

        return $this;
    }

    public function withRecordedAt(DateTimeImmutable $recordedAt): self
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
            parameterId: $this->parameterId ?? Uuid::v4(),
            value: $this->value ?? $faker->randomFloat(5, 1, 50),
            recordedAt: $this->recordedAt ?? new DateTimeImmutable(),
            id: $this->id ?? Uuid::v4()
        );
    }
}