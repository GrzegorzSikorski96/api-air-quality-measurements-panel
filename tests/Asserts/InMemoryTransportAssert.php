<?php

declare(strict_types=1);

namespace App\Tests\Asserts;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class InMemoryTransportAssert
{
    public static function assertAtLeastOneMessageInTypeWasSent(string $messageType, TransportInterface $transport): void
    {
        foreach ($transport->get() as $envelope) {
            if ($envelope->getMessage() instanceof $messageType) {
                return;
            }
        }

        throw new ExpectationFailedException(sprintf(
            'Expected message in type: "%s" not found in the sent messages.',
            $messageType
        ));
    }

    public static function assertExactCountOfSentMessages(int $exactCount, TransportInterface $transport): void
    {
        Assert::assertCount($exactCount, $transport->get());
    }
}