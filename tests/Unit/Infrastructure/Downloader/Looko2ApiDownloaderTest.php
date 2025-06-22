<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Downloader;

use App\Domain\Downloader\Config\Looko2DownloaderConfig;
use App\Domain\Downloader\DownloaderInterface;
use App\Infrastructure\Downloader\Looko2Downloader;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

final class Looko2ApiDownloaderTest extends UnitTestCase
{
    protected DownloaderInterface $downloader;

    public function setUp(): void
    {
        parent::setUp();

        $downloader = $this->container->get(Looko2Downloader::class);
        Assert::assertInstanceOf(DownloaderInterface::class, $downloader);
        $this->downloader = $downloader;
    }

    #[Test]
    public function downloadLooko2ApiMeasurements()
    {
        // given
        $givenDevice = DeviceBuilder::any()
            ->withId(Uuid::v4())
            ->withExternalId('ExternalId')
            ->withToken('Token')
            ->build();
        $this->deviceRepository->save($givenDevice);

        $givenLooko2Config = new Looko2DownloaderConfig();
        foreach ($givenLooko2Config->responseKeysToInternalParameterCodes as $internalCode) {
            $givenMeasurementParameter = MeasurementParameterBuilder::any()
                ->withCode($internalCode)
                ->build();

            $this->measurementParameterRepository->save($givenMeasurementParameter);
        }

        // when
        Assert::assertCount(0, $this->measurementRepository->findAll());
        $this->downloader->download($givenDevice->getId());
        $this->asyncTransport->process();

        // then
        Assert::assertCount(6, $this->measurementRepository->findAll());
    }
}
