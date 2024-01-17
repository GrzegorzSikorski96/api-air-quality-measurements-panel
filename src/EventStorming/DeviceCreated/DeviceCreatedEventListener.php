<?php

declare(strict_types=1);

namespace App\EventStorming\DeviceCreated;

use App\Infrastructure\Messenger\Event\EventListenerInterface;

final readonly class DeviceCreatedEventListener implements EventListenerInterface
{
    public function __invoke(DeviceCreatedEvent $event)
    {
        var_dump('asd');
    }
}