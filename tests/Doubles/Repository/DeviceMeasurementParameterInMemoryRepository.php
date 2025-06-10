<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Exception;
use Symfony\Component\Uid\Uuid;

final class DeviceMeasurementParameterInMemoryRepository implements DeviceMeasurementParameterRepositoryInterface
{
    private array $entities = [];

    public function save(DeviceMeasurementParameter $deviceMeasurementParameter): void
    {
        $this->isUnique($deviceMeasurementParameter);
        $this->entities[$deviceMeasurementParameter->getId()->toRfc4122()] = $deviceMeasurementParameter;
    }

    public function get(Uuid $deviceMeasurementParameterId): DeviceMeasurementParameter
    {
        $deviceMeasurementParameter = $this->findOne($deviceMeasurementParameterId);

        if (!$deviceMeasurementParameter) {
            throw new NonExistentEntityException(DeviceMeasurementParameter::class, $deviceMeasurementParameterId->toRfc4122());
        }

        return $deviceMeasurementParameter;
    }

    public function findOne(Uuid $deviceMeasurementParameterId): ?DeviceMeasurementParameter
    {
        return $this->entities[$deviceMeasurementParameterId->toRfc4122()] ?? null;
    }

    public function findByDeviceId(Uuid $deviceId): array
    {
        $deviceMeasurementParameters = [];

        foreach ($this->entities as $deviceMeasurementParameter) {
            if ($deviceMeasurementParameter->getDeviceId() === $deviceId) {
                $deviceMeasurementParameters[] = $deviceMeasurementParameter;
            }
        }

        return $deviceMeasurementParameters;
    }

    public function findOneByDeviceIdAndMeasurementParameterId(Uuid $deviceId, Uuid $measurementParameterId): ?DeviceMeasurementParameter
    {
        /** @var DeviceMeasurementParameter $deviceMeasurementParameter */
        foreach ($this->entities as $deviceMeasurementParameter) {
            if ($deviceMeasurementParameter->getDeviceId() === $deviceId && $deviceMeasurementParameter->getMeasurementParameterId() === $measurementParameterId) {
                return $deviceMeasurementParameter;
            }
        }

        return null;
    }

    private function isUnique(DeviceMeasurementParameter $deviceMeasurementParameter): void
    {
        /** @var DeviceMeasurementParameter $entity */
        foreach ($this->entities as $id => $entity) {
            if (
                $deviceMeasurementParameter->getId()->toRfc4122() !== $id
                && ($deviceMeasurementParameter->getDeviceId() === $entity->getDeviceId())
                && ($deviceMeasurementParameter->getMeasurementParameterId() === $entity->getMeasurementParameterId())
            ) {
                throw new Exception(sprintf('DETAIL:  Key (device_id, measurement_parameter_id)=(%s, %s) already exists.', $entity->getDeviceId()->toRfc4122(), $entity->getMeasurementParameterId()->toRfc4122()));
            }
        }
    }
}
