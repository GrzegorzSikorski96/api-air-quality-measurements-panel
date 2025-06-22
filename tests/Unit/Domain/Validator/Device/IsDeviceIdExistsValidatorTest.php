<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\Device;

use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Validator\DeviceId\IsDeviceIdExists;
use App\Domain\Validator\DeviceId\IsDeviceIdExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

final class IsDeviceIdExistsValidatorTest extends ValidatorTestCase
{
    private IsDeviceIdExists $givenConstraint;
    protected DeviceRepositoryInterface $deviceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $deviceRepository = $this->container->get(DeviceRepositoryInterface::class);
        Assert::assertInstanceOf(DeviceRepositoryInterface::class, $deviceRepository);
        $this->deviceRepository = $deviceRepository;

        $this->givenConstraint = new IsDeviceIdExists();
    }

    protected function createValidator(): IsDeviceIdExistsValidator
    {
        /* @var IsDeviceIdExistsValidator */
        return $this->container->get(IsDeviceIdExistsValidator::class);
    }

    #[Test]
    public function deviceIdDoesNotExistsInDatabase()
    {
        // given
        $givenNotExistingDeviceId = Uuid::v4();

        // when
        $this->validator->validate($givenNotExistingDeviceId, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNotExistingDeviceId->toRfc4122())
            ->setCode('404')
            ->assertRaised();
    }

    #[Test]
    public function deviceIdAlreadyExistsInDatabase()
    {
        // given
        $givenExistingId = Uuid::v4();
        $givenDevice = DeviceBuilder::any()
            ->withId($givenExistingId)
            ->build();

        $this->deviceRepository->save($givenDevice);

        // when
        $this->validator->validate($givenExistingId, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    #[Test]
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenViolationCode = '123';
        $givenNotExistingId = Uuid::v4();

        // when
        $givenConstraint = new IsDeviceIdExists($givenViolationCode);
        $this->validator->validate($givenNotExistingId, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenNotExistingId->toRfc4122())
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
