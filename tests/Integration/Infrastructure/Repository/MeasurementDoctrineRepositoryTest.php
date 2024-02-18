<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository;

use App\Domain\Entity\Measurement;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Infrastructure\Repository\MeasurementDoctrineRepository;
use App\Tests\TestTemplate\MeasurementRepositoryTestTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;

final class MeasurementDoctrineRepositoryTest extends MeasurementRepositoryTestTemplate
{
    use ReloadDatabaseTrait;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $em = $container->get(EntityManagerInterface::class);
        Assert::assertInstanceOf(ObjectManager::class, $em);
        $this->em = $em;

        $measurementRepository = $container->get(MeasurementDoctrineRepository::class);
        Assert::assertInstanceOf(MeasurementRepositoryInterface::class, $measurementRepository);
        $this->measurementRepository = $measurementRepository;
    }

    public function repository(): MeasurementRepositoryInterface
    {
        return $this->measurementRepository;
    }

    public function save(Measurement $measurement): void
    {
        $this->repository()->save($measurement);
        $this->em->flush();
        $this->em->clear();
    }
}
