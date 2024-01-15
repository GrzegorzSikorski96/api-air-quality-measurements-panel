<?php

declare(strict_types=1);

namespace App\Domain\Validator\DeviceName;

use Attribute;
use Symfony\Component\Validator\Constraint;
#[Attribute]
final class IsDeviceNameNotExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 404)
    {
        parent::__construct();

        $this->violationCode = (string) $code;
    }

    public string $message = 'Measurement Parameter with name: "{{ string }}" already exist.';
}