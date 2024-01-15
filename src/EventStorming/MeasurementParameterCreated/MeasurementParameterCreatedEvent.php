<?php

declare(strict_types=1);

namespace App\EventStorming\MeasurementParameterCreated;

use App\Infrastructure\Messenger\PrivateEventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class MeasurementParameterCreatedEvent implements PrivateEventInterface
{
    public function __construct(public Uuid $measurementParameterId)
    {
    }
}