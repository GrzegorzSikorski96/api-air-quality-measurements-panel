<?php

declare(strict_types=1);

namespace App\UseCase\CreateDevice;

use App\Domain\Validator\ApiProvider\IsOneOfApiProviders;
use App\Domain\Validator\DeviceId\IsDeviceIdNotExists;
use App\Domain\Validator\DeviceName\IsDeviceNameNotExists;
use App\Domain\Validator\Latitude\IsCorrectLatitude;
use App\Domain\Validator\Longitude\IsCorrectLongitude;
use App\Infrastructure\Messenger\Command\AsyncCommandInterface;
use App\Infrastructure\Messenger\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class CreateDeviceCommand implements AsyncCommandInterface, CommandInterface
{
    public function __construct(
        #[IsDeviceNameNotExists(403)]
        public string $name,
        #[IsCorrectLatitude(400)]
        public float $latitude,
        #[IsCorrectLongitude(400)]
        public float $longitude,
        #[IsOneOfApiProviders(400)]
        public string $apiProvider,
        public ?string $externalId = null,
        public ?string $token = null,
        #[IsDeviceIdNotExists(403)]
        public ?Uuid $id = null
    ) {
    }
}
