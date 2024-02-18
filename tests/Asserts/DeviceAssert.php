<?php

declare(strict_types=1);

namespace App\Tests\Asserts;

use App\Domain\Entity\Device;
use PHPUnit\Framework\Assert;

final class DeviceAssert
{
    public static function assertDevicesEquals(Device $expected, Device $actual): void
    {
        Assert::assertEquals($expected->getId(), $actual->getId());
        Assert::assertEquals($expected->getName(), $actual->getName());
        Assert::assertEquals($expected->getLatitude(), $actual->getLatitude());
        Assert::assertEquals($expected->getLongitude(), $actual->getLongitude());
        Assert::assertEquals($expected->getProvider(), $actual->getProvider());
        Assert::assertEquals($expected->getExternalId(), $actual->getExternalId());
        Assert::assertEquals($expected->getToken(), $actual->getToken());
        Assert::assertEquals($expected->getCreatedAt()->format('YYYY-mm-dd HH:i:s'), $actual->getCreatedAt()->format('YYYY-mm-dd HH:i:s'));
    }
}
