<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Tests\Doubles\Repository\MeasurementParameterInMemoryRepository;
use App\Tests\TestTemplate\MeasurementParameterRepositoryTestTemplate;

final class MeasurementParameterInMemoryRepositoryTest extends MeasurementParameterRepositoryTestTemplate
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->measurementParameterRepository = new MeasurementParameterInMemoryRepository();
    }

    protected function repository(): MeasurementParameterRepositoryInterface
    {
        return $this->measurementParameterRepository;
    }

    protected function save(MeasurementParameter $measurementParameter): void
    {
        $this->repository()->save($measurementParameter);
    }
}
