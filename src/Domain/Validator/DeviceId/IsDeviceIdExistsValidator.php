<?php

declare(strict_types=1);

namespace App\Domain\Validator\DeviceId;

use App\Domain\Repository\DeviceRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsDeviceIdExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsDeviceIdExists) {
            throw new UnexpectedTypeException($constraint, IsDeviceIdExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var Uuid $value */
        if (is_null($this->deviceRepository->findOne($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->toRfc4122())
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
