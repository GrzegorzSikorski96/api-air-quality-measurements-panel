<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterInMemoryRepository implements MeasurementParameterRepositoryInterface
{
    private array $entities = [];

    public function save(MeasurementParameter $measurementParameter): void
    {
        $this->entities[$measurementParameter->getId()->toRfc4122()] = $measurementParameter;
    }

    public function get(Uuid $id): MeasurementParameter
    {
        $measurementParameter = $this->findOne($id);

        if(!$measurementParameter) {
            throw new NonExistentEntityException(MeasurementParameter::class, $id->toRfc4122());
        }

        return $measurementParameter;
    }

    public function findOne(Uuid $id): ?MeasurementParameter
    {
        return $this->entities[$id->toRfc4122()] ?? null;
    }

    public function findOneByName(string $name): ?MeasurementParameter
    {
        /** @var MeasurementParameter $entity */
        foreach ($this->entities as $entity) {
            if ($name === $entity->getName()) {
                return $entity;
            }
        }
        return null;
    }

    public function findOneByCode(string $code): ?MeasurementParameter
    {
        /** @var MeasurementParameter $entity */
        foreach ($this->entities as $entity) {
            if ($code === $entity->getCode()) {
                return $entity;
            }
        }
        return null;
    }

    public function findOneByFormula(string $formula): ?MeasurementParameter
    {
        /** @var MeasurementParameter $entity */
        foreach ($this->entities as $entity) {
            if ($formula === $entity->getFormula()) {
                return $entity;
            }
        }
        return null;
    }

    /** @return MeasurementParameter[] */
    public function findAll(): array
    {
        return $this->entities;
    }
}