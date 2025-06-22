<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\Device;

use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Validator\DeviceName\IsDeviceNameNotExists;
use App\Domain\Validator\DeviceName\IsDeviceNameNotExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;

final class IsDeviceNameNotExistsValidatorTest extends ValidatorTestCase
{
    private IsDeviceNameNotExists $givenConstraint;
    protected DeviceRepositoryInterface $deviceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $deviceRepository = $this->container->get(DeviceRepositoryInterface::class);
        Assert::assertInstanceOf(DeviceRepositoryInterface::class, $deviceRepository);
        $this->deviceRepository = $deviceRepository;

        $this->givenConstraint = new IsDeviceNameNotExists();
    }

    protected function createValidator(): IsDeviceNameNotExistsValidator
    {
        /* @var IsDeviceNameNotExistsValidator */
        return $this->container->get(IsDeviceNameNotExistsValidator::class);
    }

    #[Test]
    public function deviceNameExistsInDatabase()
    {
        // given
        $givenExistingName = 'Existing name';
        $givenDevice = DeviceBuilder::any()
            ->withName($givenExistingName)
            ->build();
        $this->deviceRepository->save($givenDevice);

        // when
        $this->validator->validate($givenExistingName, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingName)
            ->setCode('403')
            ->assertRaised();
    }

    #[Test]
    public function deviceNameNotExistsInDatabase()
    {
        // given
        $givenNotExistingName = 'Not existing name';
        $givenDevice = DeviceBuilder::any()->build();

        $this->deviceRepository->save($givenDevice);

        // when
        $this->validator->validate($givenNotExistingName, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    #[Test]
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenViolationCode = '123';
        $givenExistingName = 'Existing name';
        $givenDevice = DeviceBuilder::any()
            ->withName($givenExistingName)
            ->build();
        $this->deviceRepository->save($givenDevice);

        // when
        $givenConstraint = new IsDeviceNameNotExists($givenViolationCode);
        $this->validator->validate($givenExistingName, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingName)
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
