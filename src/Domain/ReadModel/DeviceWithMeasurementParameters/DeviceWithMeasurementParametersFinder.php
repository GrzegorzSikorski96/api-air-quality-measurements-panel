<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\DeviceWithMeasurementParameters;

use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\ReadModel\MeasurementParameter\MeasurementParameterPresenter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Messenger\Query\QueryFinderInterface;

final readonly class DeviceWithMeasurementParametersFinder implements QueryFinderInterface
{
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
        private DeviceMeasurementParameterRepositoryInterface $deviceMeasurementParameterRepository,
        private MeasurementParameterRepositoryInterface $measurementParameterRepository,
    ) {
    }

    public function __invoke(DeviceWithMeasurementParametersQuery $query): ?DeviceWithMeasurementParametersPresenter
    {
        $device = $this->deviceRepository->findOne($query->id);

        if (! $device) {
            return null;
        }

        $deviceMeasurementParameters = $this->deviceMeasurementParameterRepository->findByDeviceId($device->getId());

        $measurementParameters = [];
        /** @var DeviceMeasurementParameter $deviceMeasurementParameter */
        foreach ($deviceMeasurementParameters as $deviceMeasurementParameter) {
            $measurementParameter = $this->measurementParameterRepository->get(
                $deviceMeasurementParameter->getMeasurementParameterId()
            );
            $measurementParameters[] = new MeasurementParameterPresenter($measurementParameter);
        }

        return new DeviceWithMeasurementParametersPresenter($device, $measurementParameters);
    }
}
