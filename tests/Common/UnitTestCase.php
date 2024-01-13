<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\MeasurementRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UnitTestCase extends KernelTestCase
{
    use PrepareInMemoryRepositoryTrait;

    protected ContainerInterface $container;
    protected Generator $faker;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;
    protected MeasurementRepositoryInterface $measurementRepository;
    protected ObjectManager $em;
    protected MessageBusInterface $commandBus;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();

        $this->substituteRepositoryInMemoryImplementation();

        $em = $this->container->get(EntityManagerInterface::class);
        Assert::assertInstanceOf(ObjectManager::class, $em);
        $this->em = $em;

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $measurementRepository = $this->container->get(MeasurementRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementRepositoryInterface::class, $measurementRepository);
        $this->measurementRepository = $measurementRepository;

        $commandBus = $this->container->get(MessageBusInterface::class);
        Assert::assertInstanceOf(MessageBusInterface::class, $commandBus);
        $this->commandBus = $commandBus;

        $this->faker = Factory::create();
    }
}