<?php

declare(strict_types=1);

namespace App\Tests\Asserts;

use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Uid\Uuid;

final class UuidAssert
{
    public static function assertUuid(string $uuid): void
    {
        try {
            Uuid::fromString($uuid);
        } catch (\InvalidArgumentException $e) {
            throw new ExpectationFailedException(sprintf('String "%s" is not valid UUID', $uuid));
        }
    }
}
