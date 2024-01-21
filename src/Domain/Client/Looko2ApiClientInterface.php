<?php

declare(strict_types=1);

namespace App\Domain\Client;

interface Looko2ApiClientInterface
{
    public function getLastDeviceMeasurement(string $externalDeviceId, string $token): array;
}
