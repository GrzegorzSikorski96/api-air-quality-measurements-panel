<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Device;

use App\Domain\Repository\DeviceRepositoryInterface;
use App\Infrastructure\Messenger\Query\QueryFinderInterface;

final readonly class DeviceFinder implements QueryFinderInterface
{
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
    ) {
    }

    public function __invoke(DeviceQuery $query): ?DevicePresenter
    {
        $device = $this->deviceRepository->findOne($query->id);

        if (!$device) {
            return null;
        }

        return new DevicePresenter($device);
    }
}
