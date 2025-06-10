<?php

declare(strict_types=1);

namespace App\Domain\Validator\MeasurementParameterId;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsMeasurementParameterIdExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 404)
    {
        parent::__construct();

        $this->violationCode = (string) $code;
    }

    public string $message = 'Measurement Parameter with id: "{{ string }}" does not exist.';
}
