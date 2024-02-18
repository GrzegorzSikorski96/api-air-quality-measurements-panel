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
    private array $uniqueFields = [];

    public function save(Measurement $measurement): void
    {
        $this->isUnique($measurement);
        $this->entities[$measurement->getId()->toRfc4122()] = $measurement;
    }

    public function get(Uuid $id): Measurement
    {
        $measurement = $this->findOne($id);

        if (!$measurement) {
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

    public function findByDeviceAndParameterInTimeRange(Uuid $deviceId, Uuid $measurementParameterId, \DateTime $startDateTime, \DateTime $endDateTime = null): array
    {
        $measurements = [];

        /** @var Measurement $measurement */
        foreach ($this->entities as $measurement) {
            if (
                $measurement->getDeviceId()->toRfc4122() === $deviceId->toRfc4122()
                && $measurement->getMeasurementParameterId()->toRfc4122() === $measurementParameterId->toRfc4122()
                && $measurement->getRecordedAt() >= $startDateTime
            ) {
                if (is_null($endDateTime) || $measurement->getRecordedAt() <= $endDateTime) {
                    $measurements[] = $measurement;
                }
            }
        }

        usort($measurements, fn ($firstMeasurement, $secondMeasurement) => $firstMeasurement->getRecordedAt() <=> $secondMeasurement->getRecordedAt());

        return $measurements;
    }

    private function isUnique(Measurement $measurement): void
    {
        /** @var Measurement $entity */
        foreach ($this->entities as $id => $entity) {
            if ($measurement->getId()->toRfc4122() !== $id) {
                foreach ($this->uniqueFields as $field) {
                    $fieldAccessor = sprintf('get%s', ucfirst($field));
                    if ($entity->$fieldAccessor() === $measurement->$fieldAccessor()) {
                        throw new \Exception(sprintf('DETAIL:  Key (%s)=(%s) already exists.', $field, $measurement->$fieldAccessor()));
                    }
                }
            }
        }
    }
}
