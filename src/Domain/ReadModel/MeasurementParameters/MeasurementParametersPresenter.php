<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\MeasurementParameters;

use App\Domain\ReadModel\MeasurementParameter\MeasurementParameterPresenter;

final class MeasurementParametersPresenter
{
    public array $measurementParameters = [];

    public function __construct(array $measurementParameters)
    {
        foreach ($measurementParameters as $measurementParameter) {
            $this->measurementParameters[] = new MeasurementParameterPresenter($measurementParameter);
        }
    }
}
