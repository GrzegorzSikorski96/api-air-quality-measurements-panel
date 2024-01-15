<?php

declare(strict_types=1);

namespace App\UseCase\CreateDevice;

use App\Infrastructure\Messenger\AsyncCommandInterface;

final readonly class CreateDeviceCommand implements AsyncCommandInterface
{
    public function __construct(
        public string $name,
        public float $latitude,
        public float $longitude,
        public string $apiProvider,
        public ?string $externalId = null,
        public ?string $token = null
    ) {
    }
}