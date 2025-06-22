<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\Device;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Asserts\DeviceAssert;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

abstract class DeviceRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): DeviceRepositoryInterface;

    abstract protected function save(Device $device): void;

    #[Test]
    public function saveAndGetDevice(): void
    {
        // given
        $givenDeviceId = Uuid::v4();
        $givenDevice = DeviceBuilder::any()
            ->withId($givenDeviceId)
            ->build();
        // when
        $this->save($givenDevice);
        $device = $this->repository()->get($givenDevice->getId());

        // then
        Assert::assertNotNull($device);
        DeviceAssert::assertDevicesEquals($givenDevice, $device);
    }

    #[Test]
    public function throwExceptionWhenDeviceWithNameAlreadyExists(): void
    {
        // given
        $firstDeviceName = 'Device name';
        $givenFirstDevice = DeviceBuilder::any()
            ->withId(Uuid::v4())
            ->withName($firstDeviceName)
            ->build();
        $this->save($givenFirstDevice);

        $givenSecondDevice = DeviceBuilder::any()
            ->withName($firstDeviceName)
            ->withId(Uuid::v4())
            ->build();

        // except
        $this->expectExceptionMessageMatches(
            sprintf(
                "/DETAIL:  Key \(name\)=\(%s\) already exists\./i",
                $firstDeviceName
            )
        );

        // when
        $this->save($givenSecondDevice);
    }

    #[Test]
    public function findOne(): void
    {
        // given
        $givenDeviceId = Uuid::v4();
        $givenDevice = DeviceBuilder::any()
            ->withId($givenDeviceId)
            ->build();

        // when
        $this->save($givenDevice);
        $device = $this->repository()->findOne($givenDeviceId);

        // then
        Assert::assertNotNull($device);
        DeviceAssert::assertDevicesEquals($givenDevice, $device);
    }

    #[Test]
    public function findAll()
    {
        // given
        $givenFirstDevice = DeviceBuilder::any()->build();
        $this->save($givenFirstDevice);

        $givenSecondDevice = DeviceBuilder::any()->build();
        $this->save($givenSecondDevice);

        $givenThirdDevice = DeviceBuilder::any()->build();
        $this->save($givenThirdDevice);

        // when
        $allDevices = $this->repository()->findAll();

        // then
        Assert::assertCount(3, $allDevices);
        Assert::assertContainsOnlyInstancesOf(Device::class, $allDevices);
    }

    #[Test]
    public function dontFind()
    {
        // given

        // when
        $device = $this->repository()->findOne(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));

        // then
        Assert::assertNull($device);
    }

    #[Test]
    public function dontGet()
    {
        // expect
        $this->expectException(NonExistentEntityException::class);

        // when
        $this->repository()->get(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));
    }
}
