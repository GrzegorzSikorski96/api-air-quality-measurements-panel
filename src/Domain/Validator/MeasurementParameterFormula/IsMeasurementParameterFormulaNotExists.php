<?php

declare(strict_types=1);

namespace App\Domain\Validator\MeasurementParameterFormula;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsMeasurementParameterFormulaNotExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 403)
    {
        parent::__construct();

        $this->violationCode = (string) $code;
    }

    public string $message = 'Measurement Parameter with formula: "{{ string }}" already exist.';
}