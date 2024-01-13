<?php

declare(strict_types=1);

namespace App\Tests\Unit\Asserts;

use App\Domain\Entity\Device;
use PHPUnit\Framework\Assert;

final class DeviceAssert
{
    public static function assertDevicesEquals(Device $expected, Device $actual)
    {
        Assert::assertEquals($expected->getId(), $expected->getId());
        Assert::assertEquals($expected->getName(), $expected->getName());
        Assert::assertEquals($expected->getLatitude(), $expected->getLatitude());
        Assert::assertEquals($expected->getLongitude(), $expected->getLongitude());
        Assert::assertEquals($expected->getProvider(), $expected->getProvider());
        Assert::assertEquals($expected->getExternalId(), $expected->getExternalId());
        Assert::assertEquals($expected->getToken(), $expected->getToken());
        Assert::assertEquals($expected->getCreatedAt()->format('YYYY-mm-dd HH:i:s'), $expected->getCreatedAt()->format('YYYY-mm-dd HH:i:s'));
    }
}