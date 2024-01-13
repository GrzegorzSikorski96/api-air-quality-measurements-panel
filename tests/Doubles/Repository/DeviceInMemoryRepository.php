<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\Device;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Symfony\Component\Uid\Uuid;

final class DeviceInMemoryRepository implements DeviceRepositoryInterface
{
    private array $entities = [];

    public function save(Device $device): void
    {
        $this->entities[$device->getId()->toRfc4122()] = $device;
    }

    public function get(Uuid $id): Device
    {
        $device = $this->findOne($id);

        if(!$device) {
            throw new NonExistentEntityException(Device::class, $id->toRfc4122());
        }

        return $device;
    }

    public function findOne(Uuid $id): ?Device
    {
        return $this->entities[$id->toRfc4122()] ?? null;
    }

    /** @return Device[] */
    public function findAll(): array
    {
        return $this->entities;
    }
}