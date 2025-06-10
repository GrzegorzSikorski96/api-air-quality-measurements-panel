<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Device;
use Symfony\Component\Uid\Uuid;

interface DeviceRepositoryInterface
{
    public function findAll(): array;

    public function findOne(Uuid $deviceId): ?Device;

    public function findOneByName(string $name): ?Device;

    public function get(Uuid $deviceId): Device;

    public function save(Device $device): void;
}
