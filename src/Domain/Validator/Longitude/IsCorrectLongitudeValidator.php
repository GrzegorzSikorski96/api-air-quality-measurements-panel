<?php

declare(strict_types=1);

namespace App\Domain\Validator\Longitude;

use App\Domain\Entity\Enum\ApiProviderEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsCorrectLongitudeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsCorrectLongitude) {
            throw new UnexpectedTypeException($constraint, IsCorrectLongitude::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var float $value */
        if (!($value >= -180 && $value <= 180)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', strval($value))
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}