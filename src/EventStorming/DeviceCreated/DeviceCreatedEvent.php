<?php

declare(strict_types=1);

namespace App\EventStorming\DeviceCreated;

use App\Infrastructure\Messenger\PrivateEventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class DeviceCreatedEvent implements PrivateEventInterface
{
    public function __construct(public Uuid $deviceId)
    {
    }
}