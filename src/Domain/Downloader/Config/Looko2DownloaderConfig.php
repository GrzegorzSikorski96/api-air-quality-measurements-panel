<?php

declare(strict_types=1);

namespace App\Domain\Downloader\Config;

final class Looko2DownloaderConfig
{
    public array $responseKeysToInternalParameterCodes = [
        'PM1' => 'PM1',
        'PM25' => 'PM2.5',
        'PM10' => 'PM10',
        'IJP' => 'AQI',
        'Temperature' => 'Temp',
        'Humidity' => 'Humi',
    ];
}
