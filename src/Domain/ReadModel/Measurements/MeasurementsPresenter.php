<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Measurements;

use App\Domain\Entity\Measurement;
use App\Domain\ReadModel\Measurement\MeasurementPresenter;

final class MeasurementsPresenter
{
    public array $measurements = [];

    public function __construct(array $measurements)
    {
        /** @var Measurement $measurement */
        foreach ($measurements as $measurement) {
            $this->measurements[] = new MeasurementPresenter($measurement);
        }
    }
}
