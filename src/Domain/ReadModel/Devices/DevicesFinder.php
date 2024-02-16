<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\Devices;

use App\Domain\Repository\DeviceRepositoryInterface;
use App\Infrastructure\Messenger\Query\QueryFinderInterface;

final class DevicesFinder implements QueryFinderInterface
{
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository
    ) {
    }

    public function __invoke(DevicesQuery $query): DevicesPresenter
    {
        return new DevicesPresenter($this->deviceRepository->findAll());
    }
}
