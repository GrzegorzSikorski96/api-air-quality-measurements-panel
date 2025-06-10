<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\Command;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CommandBus
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function dispatch(CommandInterface $command): Envelope
    {
        return $this->commandBus->dispatch($command);
    }
}
