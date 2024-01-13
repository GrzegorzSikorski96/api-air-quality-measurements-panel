<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Domain\Entity\Measurement;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Tests\Doubles\Repository\MeasurementInMemoryRepository;
use App\Tests\TestTemplate\MeasurementRepositoryTestTemplate;

final class MeasurementInMemoryRepositoryTest extends MeasurementRepositoryTestTemplate
{
    protected function setUp():void
    {
        parent::setUp();
        $this->measurementRepository = new MeasurementInMemoryRepository();
    }

    protected function repository(): MeasurementRepositoryInterface
    {
        return $this->measurementRepository;
    }

    protected function save(Measurement $measurement): void
    {
        $this->repository()->save($measurement);
    }
}