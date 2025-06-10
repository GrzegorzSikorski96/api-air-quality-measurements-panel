<?php

declare(strict_types=1);

namespace App\Infrastructure\Downloader;

use App\Domain\Client\Looko2ApiClientInterface;
use App\Domain\Downloader\Config\Looko2DownloaderConfig;
use App\Domain\Downloader\DownloaderInterface;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Messenger\Command\CommandBus;
use App\UseCase\CreateMeasurement\CreateMeasurementCommand;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final readonly class Looko2Downloader implements DownloaderInterface
{
    public function __construct(
        private CommandBus $commandBus,
        private Looko2ApiClientInterface $apiClient,
        private DeviceRepositoryInterface $deviceRepository,
        private MeasurementParameterRepositoryInterface $measurementParameterRepository,
        private Looko2DownloaderConfig $config,
    ) {
    }

    public function download(Uuid $deviceId): void
    {
        $device = $this->deviceRepository->findOne($deviceId);
        $reportedAt = new DateTimeImmutable('now');

        $response = $this->apiClient->getLastDeviceMeasurement($device->getExternalId(), $device->getToken());

        foreach ($this->config->responseKeysToInternalParameterCodes as $responseKey => $parameterCode) {
            $measurementParameter = $this->measurementParameterRepository->findOneByCode($parameterCode);

            if (is_null($measurementParameter)) {
                continue;
            }

            $createMeasurement = new CreateMeasurementCommand(
                measurementParameterId: $measurementParameter->getId(),
                deviceId: $device->getId(),
                value: floatval($response[$responseKey]),
                recordedAt: $reportedAt
            );

            $this->commandBus->dispatch($createMeasurement);
        }
    }
}
