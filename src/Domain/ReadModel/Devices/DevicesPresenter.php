<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Devices;

use App\Domain\ReadModel\Device\DevicePresenter;

final class DevicesPresenter
{
    public array $devices = [];

    public function __construct(array $devices)
    {
        foreach ($devices as $device) {
            $this->devices[] = new DevicePresenter($device);
        }
    }
}
