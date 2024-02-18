<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Tests\Doubles\Repository\DeviceInMemoryRepository;
use App\Tests\Doubles\Repository\DeviceMeasurementParameterInMemoryRepository;
use App\Tests\Doubles\Repository\MeasurementInMemoryRepository;
use App\Tests\Doubles\Repository\MeasurementParameterInMemoryRepository;

trait PrepareInMemoryRepositoryTrait
{
    private function substituteRepositoryInMemoryImplementation(): void
    {
        $this->container->set(MeasurementParameterRepositoryInterface::class, new MeasurementParameterInMemoryRepository());
        $this->container->set(MeasurementRepositoryInterface::class, new MeasurementInMemoryRepository());
        $this->container->set(DeviceRepositoryInterface::class, new DeviceInMemoryRepository());
        $this->container->set(DeviceMeasurementParameterRepositoryInterface::class, new DeviceMeasurementParameterInMemoryRepository());
    }
}
