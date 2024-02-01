<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\MeasurementParameter;

use App\Domain\Entity\MeasurementParameter;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterPresenter
{
    public Uuid $id;
    public string $name;
    public string $code;
    public string $formula;

    public function __construct(MeasurementParameter $measurementParameter)
    {
        $this->id = $measurementParameter->getId();
        $this->name = $measurementParameter->getName();
        $this->code = $measurementParameter->getCode();
        $this->formula = $measurementParameter->getFormula();
    }
}
