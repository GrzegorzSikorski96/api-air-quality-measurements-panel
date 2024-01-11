<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository;

use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Repository\MeasurementParameterDoctrineRepository;
use App\Tests\TestTemplate\MeasurementParameterRepositoryTestTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;

final class MeasurementParameterDoctrineRepositoryTest extends MeasurementParameterRepositoryTestTemplate
{
    use ReloadDatabaseTrait;

    private ObjectManager $em;
    private MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $em = $container->get(EntityManagerInterface::class);
        Assert::assertInstanceOf(ObjectManager::class, $em);
        $this->em = $em;

        $measurementParameterRepository = $container->get(MeasurementParameterDoctrineRepository::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;
    }

    public function repository(): MeasurementParameterRepositoryInterface
    {
        return $this->measurementParameterRepository;
    }

    public function save(MeasurementParameter $measurementParameter): void
    {
        $this->repository()->save($measurementParameter);
        $this->em->flush();
        $this->em->clear();
    }
}