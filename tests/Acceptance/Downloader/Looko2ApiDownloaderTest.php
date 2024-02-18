<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Downloader;

use App\Domain\Downloader\Config\Looko2DownloaderConfig;
use App\Domain\Downloader\DownloaderInterface;
use App\EventStorming\MeasurementCreated\MeasurementCreatedEvent;
use App\EventStorming\MeasurementParameterAssignedToDevice\MeasurementParameterAssignedToDeviceEvent;
use App\Infrastructure\Downloader\Looko2Downloader;
use App\Tests\Common\AcceptanceTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use App\UseCase\AssignMeasurementParameterToDevice\AssignMeasurementParameterToDeviceCommand;
use App\UseCase\CreateMeasurement\CreateMeasurementCommand;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class Looko2ApiDownloaderTest extends AcceptanceTestCase
{
    protected DownloaderInterface $downloader;

    public function setUp(): void
    {
        parent::setUp();

        $downloader = $this->container->get(Looko2Downloader::class);
        Assert::assertInstanceOf(DownloaderInterface::class, $downloader);
        $this->downloader = $downloader;
    }

    /** @test */
    public function downloadLooko2ApiMeasurements()
    {
        // given
        $givenDevice = DeviceBuilder::any()
            ->withId(Uuid::v4())
            ->withExternalId('ExternalId')
            ->withToken('Token')
            ->build();
        $this->handleCreateDevice($givenDevice);

        $givenLooko2Config = new Looko2DownloaderConfig();
        foreach ($givenLooko2Config->responseKeysToInternalParameterCodes as $internalCode) {
            $givenMeasurementParameter = MeasurementParameterBuilder::any()
                ->withCode($internalCode)
                ->build();

            $this->handleCreateMeasurementParameter($givenMeasurementParameter);
        }

        // when
        $this->downloader->download($givenDevice->getId());
        $this->asyncTransport->process();

        // then
        $this->asyncTransport->dispatched()->assertContains(CreateMeasurementCommand::class, count($givenLooko2Config->responseKeysToInternalParameterCodes));
        $this->asyncTransport->dispatched()->assertContains(MeasurementCreatedEvent::class, count($givenLooko2Config->responseKeysToInternalParameterCodes));
        $this->asyncTransport->dispatched()->assertContains(AssignMeasurementParameterToDeviceCommand::class, count($givenLooko2Config->responseKeysToInternalParameterCodes));
        $this->asyncTransport->dispatched()->assertContains(MeasurementParameterAssignedToDeviceEvent::class, count($givenLooko2Config->responseKeysToInternalParameterCodes));
    }
}
