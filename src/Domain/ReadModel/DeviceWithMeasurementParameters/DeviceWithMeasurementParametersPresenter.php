<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\DeviceWithMeasurementParameters;

use App\Domain\Entity\Device;
use App\Domain\Entity\Enum\ApiProviderEnum;
use Symfony\Component\Uid\Uuid;

final class DeviceWithMeasurementParametersPresenter
{
    public Uuid $id;
    public string $name;
    public float $latitude;
    public float $longitude;
    public ApiProviderEnum $provider;
    public array $measurementParameters = [];

    public function __construct(Device $device, array $measurementParameterMeasurements)
    {
        $this->id = $device->getId();
        $this->name = $device->getName();
        $this->latitude = $device->getLatitude();
        $this->longitude = $device->getLongitude();
        $this->provider = $device->getProvider();

        $this->measurementParameters = $measurementParameterMeasurements;
    }
}
