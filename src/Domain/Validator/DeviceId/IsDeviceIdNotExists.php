<?php

declare(strict_types=1);

namespace App\Domain\Validator\DeviceId;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class IsDeviceIdNotExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 403)
    {
        parent::__construct();

        $this->violationCode = (string) $code;
    }

    public string $message = 'Device with id: "{{ string }}" already exist.';
}
