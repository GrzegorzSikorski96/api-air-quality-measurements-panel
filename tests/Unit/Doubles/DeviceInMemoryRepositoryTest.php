<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Domain\Entity\Device;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Tests\Doubles\Repository\DeviceInMemoryRepository;
use App\Tests\TestTemplate\DeviceRepositoryTestTemplate;

final class DeviceInMemoryRepositoryTest extends DeviceRepositoryTestTemplate
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->deviceRepository = new DeviceInMemoryRepository();
    }

    protected function repository(): DeviceRepositoryInterface
    {
        return $this->deviceRepository;
    }

    protected function save(Device $device): void
    {
        $this->repository()->save($device);
    }
}
