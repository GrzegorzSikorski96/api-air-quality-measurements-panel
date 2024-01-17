<?php

declare(strict_types=1);

namespace App\UseCase\CreateDevice;

use App\Domain\Validator\ApiProvider\IsOneOfApiProviders;
use App\Domain\Validator\DeviceName\IsDeviceNameNotExists;
use App\Domain\Validator\Latitude\IsCorrectLatitude;
use App\Infrastructure\Messenger\Command\AsyncCommandInterface;
use App\Infrastructure\Messenger\Command\CommandInterface;

final readonly class CreateDeviceCommand implements AsyncCommandInterface, CommandInterface
{
    public function __construct(
        #[IsDeviceNameNotExists(403)]
        public string $name,
        #[IsCorrectLatitude(400)]
        public float $latitude,
        #[IsCorrectLatitude(400)]
        public float $longitude,
        #[IsOneOfApiProviders(400)]
        public string $apiProvider,
        public ?string $externalId = null,
        public ?string $token = null
    ) {
    }
}