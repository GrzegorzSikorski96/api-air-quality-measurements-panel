<?php

declare(strict_types=1);

namespace App\Domain\Validator\DeviceName;

use App\Domain\Repository\DeviceRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsDeviceNameNotExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof IsDeviceNameNotExists) {
            throw new UnexpectedTypeException($constraint, IsDeviceNameNotExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var string $value */
        if (! is_null($this->deviceRepository->findOneByName($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
