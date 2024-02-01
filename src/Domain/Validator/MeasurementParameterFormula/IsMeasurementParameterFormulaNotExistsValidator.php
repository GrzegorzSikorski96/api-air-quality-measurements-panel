<?php

declare(strict_types=1);

namespace App\Domain\Validator\MeasurementParameterFormula;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsMeasurementParameterFormulaNotExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly MeasurementParameterRepositoryInterface $measurementParameterRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsMeasurementParameterFormulaNotExists) {
            throw new UnexpectedTypeException($constraint, IsMeasurementParameterFormulaNotExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var string $value */
        if (!is_null($this->measurementParameterRepository->findOneByFormula($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
