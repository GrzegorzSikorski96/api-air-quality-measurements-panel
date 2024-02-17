<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Measurements;

use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Infrastructure\Messenger\Query\QueryFinderInterface;

final class MeasurementsFinder implements QueryFinderInterface
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository
    ) {
    }

    public function __invoke(MeasurementsQuery $query): MeasurementsPresenter
    {
        $measurements = $this->measurementRepository->findByDeviceAndParameterInTimeRange(
            deviceId: $query->deviceId,
            measurementParameterId: $query->measurementParameterId,
            startDateTime: $query->startDateTime,
            endDateTime: $query->endDateTime
        );

        return new MeasurementsPresenter($measurements);
    }
}
