<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\Device;
use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Asserts\DeviceAssert;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\DeviceBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

abstract class DeviceRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): DeviceRepositoryInterface;
    abstract protected function save(Device $device): void;

    /** @test */
    public function save_and_get_device(): void
    {
        // given
        $givenDeviceId = Uuid::v4();
        $givenDevice = DeviceBuilder::any()
            ->withId($givenDeviceId)
            ->build();
        //when
        $this->save($givenDevice);
        $device = $this->repository()->get($givenDevice->getId());

        //then
        Assert::assertNotNull($device);
        DeviceAssert::assertDevicesEquals($givenDevice, $device);
    }

    /** @test */
    public function throw_exception_when_device_with_name_already_exists(): void
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

        //except
        $this->expectExceptionMessageMatches("/DETAIL:  Key \(name\)=\(Device name\) already exists\./i");

        //when
        $this->save($givenSecondDevice);
    }

    /** @test */
    public function find_one(): void
    {
        // given
        $givenDeviceId = Uuid::v4();
        $givenDevice = DeviceBuilder::any()
            ->withId($givenDeviceId)
            ->build();

        //when
        $this->save($givenDevice);
        $device = $this->repository()->findOne($givenDeviceId);

        //then
        Assert::assertNotNull($device);
        DeviceAssert::assertDevicesEquals($givenDevice, $device);
    }

    /** @test */
    public function find_all()
    {
        //given
        $givenFirstDevice = DeviceBuilder::any()->build();
        $this->save($givenFirstDevice);

        $givenSecondDevice = DeviceBuilder::any()->build();
        $this->save($givenSecondDevice);

        $givenThirdDevice = DeviceBuilder::any()->build();
        $this->save($givenThirdDevice);

        //when
        $allDevices = $this->repository()->findAll();

        //then
        Assert::assertCount(3, $allDevices);
        Assert::assertContainsOnlyInstancesOf(Device::class, $allDevices);
    }

    /** @test */
    public function dont_find()
    {
        //given

        //when
        $device = $this->repository()->findOne(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));

        //then
        Assert::assertNull($device);
    }

    /** @test */
    public function dont_get()
    {
        //expect
        $this->expectException(NonExistentEntityException::class);

        //when
        $this->repository()->get(Uuid::fromString('f8666816-444b-4f28-8658-53f7929524bc'));
    }
}