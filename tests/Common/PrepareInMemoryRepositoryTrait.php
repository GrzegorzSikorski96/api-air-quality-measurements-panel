<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Repository\DeviceDoctrineRepository;
use App\Infrastructure\Repository\MeasurementParameterDoctrineRepository;
use App\Tests\Doubles\Repository\MeasurementParameterInMemoryRepository;
use App\Tests\Doubles\Repository\DeviceInMemoryRepository;

trait PrepareInMemoryRepositoryTrait
{
    private function substituteRepositoryInMemoryImplementation(): void
    {
        $this->container->set(MeasurementParameterRepositoryInterface::class, new MeasurementParameterInMemoryRepository());
        $this->container->set(MeasurementParameterDoctrineRepository::class, new MeasurementParameterInMemoryRepository());
        $this->container->set(DeviceDoctrineRepository::class, new DeviceInMemoryRepository());
    }
}