<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\Device;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Exception;
use Symfony\Component\Uid\Uuid;

final class DeviceInMemoryRepository implements DeviceRepositoryInterface
{
    private array $entities = [];
    private array $uniqueFields = ['name'];

    public function save(Device $device): void
    {
        $this->isUnique($device);
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

    public function findOneByName(string $name): ?Device
    {
        /** @var Device $entity */
        foreach ($this->entities as $entity) {
            if ($name === $entity->getName()) {
               return $entity;
            }
        }

        return null;
    }

    /** @return Device[] */
    public function findAll(): array
    {
        return $this->entities;
    }

    private function isUnique(Device $device): void
    {
        /** @var Device $entity */
        foreach ($this->entities as $id => $entity) {
            if($device->getId()->toRfc4122() !== $id) {
                foreach ($this->uniqueFields as $field) {
                    $fieldAccessor = sprintf('get%s', ucfirst($field));
                    if ($entity->$fieldAccessor() === $device->$fieldAccessor()) {
                        throw new Exception(sprintf("DETAIL:  Key (%s)=(%s) already exists.", $field, $device->$fieldAccessor())) ;
                    }
                }
            }
        }
    }
}