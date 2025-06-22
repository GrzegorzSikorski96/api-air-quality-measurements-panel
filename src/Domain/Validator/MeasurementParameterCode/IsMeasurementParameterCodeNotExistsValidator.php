<?php

declare(strict_types=1);

namespace App\Domain\Validator\MeasurementParameterCode;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsMeasurementParameterCodeNotExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly MeasurementParameterRepositoryInterface $measurementParameterRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof IsMeasurementParameterCodeNotExists) {
            throw new UnexpectedTypeException($constraint, IsMeasurementParameterCodeNotExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var string $value */
        if (! is_null($this->measurementParameterRepository->findOneByCode($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
