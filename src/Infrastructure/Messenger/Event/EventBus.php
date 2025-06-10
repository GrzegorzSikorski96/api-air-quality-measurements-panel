<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\Event;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EventBus
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {
    }

    public function dispatch(EventInterface $event): Envelope
    {
        return $this->eventBus->dispatch($event);
    }
}
