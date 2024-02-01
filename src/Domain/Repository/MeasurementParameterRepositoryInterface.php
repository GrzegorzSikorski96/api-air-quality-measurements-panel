<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\MeasurementParameter;
use Symfony\Component\Uid\Uuid;

interface MeasurementParameterRepositoryInterface
{
    public function findAll(): array;

    public function findOne(Uuid $id): ?MeasurementParameter;

    public function findOneByName(string $name): ?MeasurementParameter;

    public function findOneByCode(string $code): ?MeasurementParameter;

    public function findOneByFormula(string $formula): ?MeasurementParameter;

    public function get(Uuid $id): MeasurementParameter;

    public function save(MeasurementParameter $measurementParameter): void;
}
