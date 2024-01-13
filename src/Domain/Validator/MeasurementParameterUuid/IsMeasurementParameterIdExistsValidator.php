<?php

declare(strict_types=1);

namespace App\Domain\Validator\MeasurementParameterUuid;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsMeasurementParameterIdExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly MeasurementParameterRepositoryInterface $measurementParameterRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsMeasurementParameterIdExists) {
            throw new UnexpectedTypeException($constraint, IsMeasurementParameterIdExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var Uuid $value */
        if (is_null($this->measurementParameterRepository->findOne($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->toRfc4122())
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}