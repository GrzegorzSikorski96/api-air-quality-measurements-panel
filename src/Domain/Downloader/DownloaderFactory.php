<?php

declare(strict_types=1);

namespace App\Domain\Downloader;

use App\Domain\Entity\Device;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class DownloaderFactory
{
    private string $fqcnTempate = 'App\Infrastructure\Downloader\\%sDownloader';
    private readonly ContainerInterface $container;

    public function __construct(
        ContainerInterface $container,
    ) {
        $this->container = $container;
    }

    public function create(Device $device): DownloaderInterface
    {
        $fqcn = sprintf($this->fqcnTempate, ucfirst($device->getProvider()->value));

        if ($this->container->has($fqcn)) {
            /** @var DownloaderInterface $downloader */
            $downloader = $this->container->get($fqcn);

            return $downloader;
        } else {
            throw new InvalidArgumentException(sprintf('Downloader not found for provider: %s', $device->getProvider()->value));
        }
    }
}
