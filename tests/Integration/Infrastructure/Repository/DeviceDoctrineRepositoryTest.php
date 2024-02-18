<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository;

use App\Domain\Entity\Device;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Infrastructure\Repository\DeviceDoctrineRepository;
use App\Tests\TestTemplate\DeviceRepositoryTestTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;

final class DeviceDoctrineRepositoryTest extends DeviceRepositoryTestTemplate
{
    use ReloadDatabaseTrait;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $em = $container->get(EntityManagerInterface::class);
        Assert::assertInstanceOf(ObjectManager::class, $em);
        $this->em = $em;

        $deviceRepository = $container->get(DeviceDoctrineRepository::class);
        Assert::assertInstanceOf(DeviceRepositoryInterface::class, $deviceRepository);
        $this->deviceRepository = $deviceRepository;
    }

    public function repository(): DeviceRepositoryInterface
    {
        return $this->deviceRepository;
    }

    public function save(Device $device): void
    {
        $this->repository()->save($device);
        $this->em->flush();
        $this->em->clear();
    }
}
