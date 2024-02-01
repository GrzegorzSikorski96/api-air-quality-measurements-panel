<?php

declare(strict_types=1);

namespace App\Domain\Validator\Longitude;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class IsCorrectLongitude extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 400)
    {
        parent::__construct();

        $this->violationCode = (string) $code;
    }

    public string $message = 'Longitude value: "{{ string }}" is not correct.';
}
