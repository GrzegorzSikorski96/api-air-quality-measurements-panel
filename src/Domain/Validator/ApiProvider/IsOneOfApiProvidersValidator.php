<?php

declare(strict_types=1);

namespace App\Domain\Validator\ApiProvider;

use App\Domain\Entity\Enum\ApiProviderEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsOneOfApiProvidersValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof IsOneOfApiProviders) {
            throw new UnexpectedTypeException($constraint, IsOneOfApiProviders::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var string $value */
        if (! in_array($value, ApiProviderEnum::getAllApiProviders())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
