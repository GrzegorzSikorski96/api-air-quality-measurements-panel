<?php

declare(strict_types=1);

namespace App\EventStorming\MeasurementCreated;

use App\Infrastructure\Messenger\Event\EventInterface;
use App\Infrastructure\Messenger\Event\PrivateEventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class MeasurementCreatedEvent implements PrivateEventInterface, EventInterface
{
    public function __construct(public Uuid $measurementId, public Uuid $deviceId, public Uuid $measurementParameterId)
    {
    }
}
