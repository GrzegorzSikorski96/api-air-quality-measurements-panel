<?php

declare(strict_types=1);

namespace App\Domain\Validator\ApiProvider;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsOneOfApiProviders extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 400)
    {
        parent::__construct();

        $this->violationCode = (string) $code;
    }

    public string $message = 'Api provider: "{{ string }}" not supported yet.';
}
