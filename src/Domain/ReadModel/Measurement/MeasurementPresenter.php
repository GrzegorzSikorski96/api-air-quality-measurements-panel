<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Measurement;

use App\Domain\Entity\Measurement;

final class MeasurementPresenter
{
    public float $value;
    public string $recordedAt;

    public function __construct(Measurement $measurement)
    {
        $this->value = $measurement->getValue();
        $this->recordedAt = $measurement->getRecordedAt()->format('Y-m-d H:i:s');
    }
}
