<?php

declare(strict_types=1);

namespace App\Tests\Common;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\Assert;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Repository\DeviceRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Zenstruck\Messenger\Test\Transport\TestTransport;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Domain\Repository\MeasurementRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;

abstract class UnitTestCase extends KernelTestCase
{
    use PrepareInMemoryRepositoryTrait;
    use PrepareInMemoryClientTrait;

    protected ContainerInterface $container;
    protected Generator $faker;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;
    protected MeasurementRepositoryInterface $measurementRepository;
    protected DeviceRepositoryInterface $deviceRepository;
    protected ObjectManager $em;
    protected MessageBusInterface $commandBus;

    /** @var TestTransport $asyncTransport */
    protected TransportInterface $asyncTransport;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();

        $this->substituteRepositoryInMemoryImplementation();
        $this->substituteClientInMemoryImplementation();

        $em = $this->container->get(EntityManagerInterface::class);
        Assert::assertInstanceOf(ObjectManager::class, $em);
        $this->em = $em;

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $measurementRepository = $this->container->get(MeasurementRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementRepositoryInterface::class, $measurementRepository);
        $this->measurementRepository = $measurementRepository;

        $deviceRepository = $this->container->get(DeviceRepositoryInterface::class);
        Assert::assertInstanceOf(DeviceRepositoryInterface::class, $deviceRepository);
        $this->deviceRepository = $deviceRepository;

        $commandBus = $this->container->get(MessageBusInterface::class);
        Assert::assertInstanceOf(MessageBusInterface::class, $commandBus);
        $this->commandBus = $commandBus;

        $asyncTransport = $this->container->get('messenger.transport.async');
        Assert::assertInstanceOf(TransportInterface::class, $asyncTransport);
        $this->asyncTransport = $asyncTransport;

        $this->faker = Factory::create();
    }

    public function selfRequest(string $method, string $endpoint, array $queryParameters = [], array $body = []): Response
    {
        /** @var KernelBrowser $client */
        $client = $this->container->get('test.client');
        $client->request($method, $endpoint, $queryParameters, [], [], json_encode($body));

        return $client->getResponse();
    }
}