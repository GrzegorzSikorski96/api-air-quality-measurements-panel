<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Repository\MeasurementParameterDoctrineRepository;
use App\Tests\Doubles\Repository\MeasurementParameterInMemoryRepository;

trait PrepareInMemoryRepositoryTrait
{
    private function substituteRepositoryInMemoryImplementation(): void
    {
        $this->container->set(MeasurementParameterRepositoryInterface::class, new MeasurementParameterInMemoryRepository());
        $this->container->set(MeasurementParameterDoctrineRepository::class, new MeasurementParameterInMemoryRepository());
    }
}