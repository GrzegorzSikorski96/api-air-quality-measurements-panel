<?php

declare(strict_types=1);

namespace App\Domain\Validator\Latitude;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsCorrectLatitudeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsCorrectLatitude) {
            throw new UnexpectedTypeException($constraint, IsCorrectLongitude::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var float $value */
        if (!($value >= -90 && $value <= 90)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', strval($value))
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}