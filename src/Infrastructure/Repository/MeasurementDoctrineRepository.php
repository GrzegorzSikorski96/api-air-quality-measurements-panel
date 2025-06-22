<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Measurement;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class MeasurementDoctrineRepository extends ServiceEntityRepository implements MeasurementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function save(Measurement $measurement): void
    {
        $this->getEntityManager()->persist($measurement);
    }

    public function get(Uuid $measurementId): Measurement
    {
        $measurement = $this->findOne($measurementId);

        if (! $measurement) {
            throw new NonExistentEntityException(Measurement::class, $measurementId->toRfc4122());
        }

        return $measurement;
    }

    public function findOne(Uuid $measurementId): ?Measurement
    {
        return $this->find($measurementId);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findByDeviceAndParameterInTimeRange(
        Uuid $deviceId,
        Uuid $measurementParameterId,
        DateTime $startDateTime,
        ?DateTime $endDateTime = null
    ): array {
        $queryBuilder = $this->createQueryBuilder('m')
        ->select('m')
        ->andWhere('m.recordedAt >= :start_date_time')
        ->andWhere('m.deviceId = :device_id')
        ->andWhere('m.measurementParameterId = :measurement_parameter_id')
        ->setParameters(
            new ArrayCollection([
                new Parameter('device_id', $deviceId),
                new Parameter('measurement_parameter_id', $measurementParameterId),
                new Parameter('start_date_time', $startDateTime),
            ])
        );

        if (! is_null($endDateTime)) {
            $queryBuilder->andWhere('m.recordedAt <= :end_date_time')
            ->setParameter('end_date_time', $endDateTime);
        }

        $queryBuilder->orderBy('m.recordedAt', 'ASC');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
