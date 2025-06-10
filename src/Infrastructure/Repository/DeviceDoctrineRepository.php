<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Device;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class DeviceDoctrineRepository extends ServiceEntityRepository implements DeviceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    public function save(Device $device): void
    {
        $this->getEntityManager()->persist($device);
    }

    public function get(Uuid $deviceId): Device
    {
        $device = $this->findOne($deviceId);

        if (!$device) {
            throw new NonExistentEntityException(Device::class, $deviceId->toRfc4122());
        }

        return $device;
    }

    public function findOne(Uuid $deviceId): ?Device
    {
        return $this->find($deviceId);
    }

    public function findOneByName(string $name): ?Device
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
