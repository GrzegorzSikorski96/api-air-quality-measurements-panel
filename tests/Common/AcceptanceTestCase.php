<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Entity\Device;
use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Entity\Measurement;
use App\Domain\Entity\MeasurementParameter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Infrastructure\Messenger\Command\CommandBus;
use App\Infrastructure\Messenger\Event\EventBus;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceCommand;
use App\UseCase\CreateDevice\CreateDeviceCommand;
use App\UseCase\CreateMeasurement\CreateMeasurementCommand;
use App\UseCase\CreateMeasurementParameter\CreateMeasurementParameterCommand;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Zenstruck\Messenger\Test\InteractsWithMessenger;
use Zenstruck\Messenger\Test\Transport\TestTransport;

abstract class AcceptanceTestCase extends KernelTestCase
{
    use PrepareInMemoryClientTrait;
    use ReloadDatabaseTrait;
    use InteractsWithMessenger;

    protected ContainerInterface $container;
    protected CommandBus $commandBus;
    protected EventBus $eventBus;

    /** @var TestTransport */
    protected TransportInterface $asyncTransport;

    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;
    protected MeasurementRepositoryInterface $measurementRepository;
    protected DeviceRepositoryInterface $deviceRepository;
    protected DeviceMeasurementParameterRepositoryInterface $deviceMeasurementParameterRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();

        $this->substituteClientInMemoryImplementation();

        $commandBus = $this->container->get(CommandBus::class);
        Assert::assertInstanceOf(CommandBus::class, $commandBus);
        $this->commandBus = $commandBus;

        $eventBus = $this->container->get(EventBus::class);
        Assert::assertInstanceOf(EventBus::class, $eventBus);
        $this->eventBus = $eventBus;

        $asyncTransport = $this->transport('async');
        Assert::assertInstanceOf(TransportInterface::class, $asyncTransport);
        $this->asyncTransport = $asyncTransport;

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(
            MeasurementParameterRepositoryInterface::class,
            $measurementParameterRepository
        );
        $this->measurementParameterRepository = $measurementParameterRepository;

        $measurementRepository = $this->container->get(MeasurementRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementRepositoryInterface::class, $measurementRepository);
        $this->measurementRepository = $measurementRepository;

        $deviceRepository = $this->container->get(DeviceRepositoryInterface::class);
        Assert::assertInstanceOf(DeviceRepositoryInterface::class, $deviceRepository);
        $this->deviceRepository = $deviceRepository;

        $deviceMeasurementParameterRepository = $this->container->get(
            DeviceMeasurementParameterRepositoryInterface::class
        );
        Assert::assertInstanceOf(
            DeviceMeasurementParameterRepositoryInterface::class,
            $deviceMeasurementParameterRepository
        );
        $this->deviceMeasurementParameterRepository = $deviceMeasurementParameterRepository;
    }

    public function handleCreateDevice(Device $device): void
    {
        $createDeviceCommand = new CreateDeviceCommand(
            name: $device->getName(),
            latitude: $device->getLatitude(),
            longitude: $device->getLongitude(),
            apiProvider: $device->getProvider()->value,
            externalId: $device->getExternalId(),
            token: $device->getToken(),
            id: $device->getId()
        );
        $this->commandBus->dispatch($createDeviceCommand);
        $this->asyncTransport->process(1);
        $this->asyncTransport->reset();
    }

    public function handleCreateMeasurementParameter(MeasurementParameter $measurementParameter): void
    {
        $createMeasurementParameterCommand = new CreateMeasurementParameterCommand(
            name: $measurementParameter->getName(),
            code: $measurementParameter->getCode(),
            formula: $measurementParameter->getFormula(),
            id: $measurementParameter->getId(),
        );
        $this->commandBus->dispatch($createMeasurementParameterCommand);
        $this->asyncTransport->process(1)->reset();
        $this->asyncTransport->reset();
    }

    public function handleAssignMeasurementParameterToDevice(
        DeviceMeasurementParameter $deviceMeasurementParameter
    ): void {
        $assignMeasurementParameterToDeviceCommand = new AssignMeasurementParameterToDeviceCommand(
            measurementParameterId: $deviceMeasurementParameter->getMeasurementParameterId(),
            deviceId: $deviceMeasurementParameter->getDeviceId(),
        );
        $this->commandBus->dispatch($assignMeasurementParameterToDeviceCommand);
        $this->asyncTransport->process(1)->reset();
        $this->asyncTransport->reset();
    }

    public function handleCreateMeasurement(Measurement $measurement): void
    {
        $createMeasurementCommand = new CreateMeasurementCommand(
            measurementParameterId: $measurement->getMeasurementParameterId(),
            deviceId: $measurement->getDeviceId(),
            value: $measurement->getValue(),
            recordedAt: $measurement->getRecordedAt()
        );
        $this->commandBus->dispatch($createMeasurementCommand);
        $this->asyncTransport->process(1)->reset();
        $this->asyncTransport->reset();
    }

    public function selfRequest(
        string $method,
        string $endpoint,
        array $queryParameters = [],
        array $body = []
    ): Response {
        /** @var KernelBrowser $client */
        $client = $this->container->get('test.client');
        $client->request($method, $endpoint, $queryParameters, [], [], json_encode($body));

        return $client->getResponse();
    }
}
