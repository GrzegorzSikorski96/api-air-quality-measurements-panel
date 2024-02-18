<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository;

use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Infrastructure\Repository\DeviceMeasurementParameterDoctrineRepository;
use App\Tests\TestTemplate\DeviceMeasurementParameterRepositoryTestTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;

final class DeviceMeasurementParameterDoctrineRepositoryTest extends DeviceMeasurementParameterRepositoryTestTemplate
{
    use ReloadDatabaseTrait;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $em = $container->get(EntityManagerInterface::class);
        Assert::assertInstanceOf(ObjectManager::class, $em);
        $this->em = $em;

        $deviceMeasurementParameterRepository = $container->get(DeviceMeasurementParameterDoctrineRepository::class);
        Assert::assertInstanceOf(DeviceMeasurementParameterRepositoryInterface::class, $deviceMeasurementParameterRepository);
        $this->deviceMeasurementParameterRepository = $deviceMeasurementParameterRepository;
    }

    public function repository(): DeviceMeasurementParameterRepositoryInterface
    {
        return $this->deviceMeasurementParameterRepository;
    }

    public function save(DeviceMeasurementParameter $deviceMeasurementParameter): void
    {
        $this->repository()->save($deviceMeasurementParameter);
        $this->em->flush();
        $this->em->clear();
    }
}
