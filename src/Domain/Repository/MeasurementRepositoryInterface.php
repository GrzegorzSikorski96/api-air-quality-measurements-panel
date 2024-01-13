<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Measurement;
use Symfony\Component\Uid\Uuid;

interface MeasurementRepositoryInterface
{
    public function findAll(): array;
    public function findOne(Uuid $id): ?Measurement;
    public function get(Uuid $id): Measurement;
    public function save(Measurement $measurement): void;
}
