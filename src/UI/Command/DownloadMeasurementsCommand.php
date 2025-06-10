<?php

declare(strict_types=1);

namespace App\UI\Command;

use App\Domain\Downloader\DownloaderFactory;
use App\Domain\Repository\DeviceRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:download-measurements',
    description: 'Download last measurements',
    aliases: ['app:measurements:download']
)]
final class DownloadMeasurementsCommand extends Command
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository,
        private readonly DownloaderFactory $downloaderFactory,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $devices = $this->deviceRepository->findAll();

        foreach ($devices as $device) {
            $downloader = $this->downloaderFactory->create($device);
            $downloader->download($device->getId());
        }

        return Command::SUCCESS;
    }
}
