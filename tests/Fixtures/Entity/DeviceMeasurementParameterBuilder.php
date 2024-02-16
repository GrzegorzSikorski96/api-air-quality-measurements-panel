<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Entity;

use Faker\Factory;
use Symfony\Component\Uid\Uuid;
use App\Domain\Entity\DeviceMeasurementParameter;

final class DeviceMeasurementParameterBuilder
{
    private Uuid $id;
    private Uuid $deviceId;
    private Uuid $measurementParameterId;

    public function withId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function withDeviceId(Uuid $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }
    public function withMeasurementParameterId(Uuid $measurementParameterId): self
    {
        $this->measurementParameterId = $measurementParameterId;

        return $this;
    }

    public static function any(): self
    {
        return new DeviceMeasurementParameterBuilder();
    }

    public function build(): DeviceMeasurementParameter
    {
        $faker = Factory::create();

        return new DeviceMeasurementParameter(
            deviceId: $this->deviceId ?? Uuid::v4(),
            measurementParameterId: $this->measurementParameterId ?? Uuid::v4(),
            id: $this->id ?? Uuid::v4()
        );
    }
}