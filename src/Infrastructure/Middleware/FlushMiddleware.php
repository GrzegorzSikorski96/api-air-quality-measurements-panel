<?php

declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class FlushMiddleware implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->em->flush();

        return $stack->next()->handle($envelope, $stack);
    }
}
