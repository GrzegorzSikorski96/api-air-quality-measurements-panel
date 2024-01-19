<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Infrastructure\Messenger\Command\CommandBus;
use App\Infrastructure\Messenger\Event\EventBus;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Symfony\Component\Messenger\Transport\TransportInterface;

abstract class AcceptanceTestCase extends KernelTestCase
{
    use ReloadDatabaseTrait;

    protected ContainerInterface $container;
    protected CommandBus $commandBus;
    protected EventBus $eventBus;
    protected TransportInterface $asyncTransport;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();

        $commandBus = $this->container->get(CommandBus::class);
        Assert::assertInstanceOf(CommandBus::class, $commandBus);
        $this->commandBus = $commandBus;

        $eventBus = $this->container->get(EventBus::class);
        Assert::assertInstanceOf(EventBus::class, $eventBus);
        $this->eventBus = $eventBus;

        /** @var InMemoryTransport $asyncTransport */
        $asyncTransport = $this->container->get('messenger.transport.async');
        Assert::assertInstanceOf(TransportInterface::class, $asyncTransport);
        $this->asyncTransport = $asyncTransport;
    }
}