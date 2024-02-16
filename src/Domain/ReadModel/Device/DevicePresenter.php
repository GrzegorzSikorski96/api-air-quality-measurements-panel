<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Device;

use App\Domain\Entity\Device;
use App\Domain\Entity\Enum\ApiProviderEnum;
use Symfony\Component\Uid\Uuid;

final class DevicePresenter
{
    public Uuid $id;
    public string $name;
    public float $latitude;
    public float $longitude;
    public ApiProviderEnum $provider;

    public function __construct(Device $device)
    {
        $this->id = $device->getId();
        $this->name = $device->getName();
        $this->latitude = $device->getLatitude();
        $this->longitude = $device->getLongitude();
        $this->provider = $device->getProvider();
    }
}
