<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterDoctrineRepository extends ServiceEntityRepository implements MeasurementParameterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeasurementParameter::class);
    }

    public function save(MeasurementParameter $measurementParameter): void
    {
        $this->getEntityManager()->persist($measurementParameter);
        $this->getEntityManager()->flush();
    }

    public function get(Uuid $id): MeasurementParameter
    {
        $measurementParameter = $this->findOne($id);

        if (!$measurementParameter) {
            throw new NonExistentEntityException(MeasurementParameter::class, $id->toRfc4122());
        }

        return $measurementParameter;
    }

    public function findOne(Uuid $id): ?MeasurementParameter
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findOneByCode(string $code): ?MeasurementParameter
    {
        return $this->findOneBy(['code' => $code]);
    }

    public function findOneByName(string $name): ?MeasurementParameter
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findOneByFormula(string $formula): ?MeasurementParameter
    {
        return $this->findOneBy(['formula' => $formula]);
    }
}
