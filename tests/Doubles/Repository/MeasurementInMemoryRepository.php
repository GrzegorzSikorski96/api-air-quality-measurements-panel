<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\Measurement;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Symfony\Component\Uid\Uuid;

final class MeasurementInMemoryRepository implements MeasurementRepositoryInterface
{
    private array $entities = [];

    public function save(Measurement $measurement): void
    {
        $this->entities[$measurement->getId()->toRfc4122()] = $measurement;
    }

    public function get(Uuid $id): Measurement
    {
        $measurement = $this->findOne($id);

        if(!$measurement) {
            throw new NonExistentEntityException(Measurement::class, $id->toRfc4122());
        }

        return $measurement;
    }

    public function findOne(Uuid $id): ?Measurement
    {
        return $this->entities[$id->toRfc4122()] ?? null;
    }

    /** @return Measurement[] */
    public function findAll(): array
    {
        return $this->entities;
    }
}