<?php

declare(strict_types=1);

namespace App\EventStorming\MeasurementParameterCreated;

use App\Infrastructure\Messenger\Event\EventInterface;
use App\Infrastructure\Messenger\Event\PrivateEventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class MeasurementParameterCreatedEvent implements PrivateEventInterface, EventInterface
{
    public function __construct(public Uuid $measurementParameterId)
    {
    }
}
