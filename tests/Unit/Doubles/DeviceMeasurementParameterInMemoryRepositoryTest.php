<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Tests\Doubles\Repository\DeviceMeasurementParameterInMemoryRepository;
use App\Tests\TestTemplate\DeviceMeasurementParameterRepositoryTestTemplate;

final class DeviceMeasurementParameterInMemoryRepositoryTest extends DeviceMeasurementParameterRepositoryTestTemplate
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->deviceMeasurementParameterRepository = new DeviceMeasurementParameterInMemoryRepository();
    }

    protected function repository(): DeviceMeasurementParameterRepositoryInterface
    {
        return $this->deviceMeasurementParameterRepository;
    }

    protected function save(DeviceMeasurementParameter $deviceMeasurementParameter): void
    {
        $this->repository()->save($deviceMeasurementParameter);
    }
}
