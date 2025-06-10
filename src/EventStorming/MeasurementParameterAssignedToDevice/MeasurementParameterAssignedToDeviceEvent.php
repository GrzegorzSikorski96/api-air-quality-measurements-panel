<?php

declare(strict_types=1);

namespace App\EventStorming\MeasurementParameterAssignedToDevice;

use App\Infrastructure\Messenger\Event\PrivateEventInterface;
use Symfony\Component\Uid\Uuid;

final readonly class MeasurementParameterAssignedToDeviceEvent implements PrivateEventInterface
{
    public function __construct(public Uuid $measurementParameterId, public Uuid $deviceId)
    {
    }
}
