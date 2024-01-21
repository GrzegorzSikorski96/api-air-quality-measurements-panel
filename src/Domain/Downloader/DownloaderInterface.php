<?php

declare(strict_types=1);

namespace App\Domain\Downloader;

use Symfony\Component\Uid\Uuid;

interface DownloaderInterface
{
    public function download(Uuid $deviceId): void;
}
