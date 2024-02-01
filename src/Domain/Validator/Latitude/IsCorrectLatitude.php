<?php

declare(strict_types=1);

namespace App\Domain\Validator\Latitude;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class IsCorrectLatitude extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 400)
    {
        parent::__construct();

        $this->violationCode = (string) $code;
    }

    public string $message = 'Latitude value: "{{ string }}" is not correct.';
}
