<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\Device;

use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Validator\DeviceId\IsDeviceIdNotExists;
use App\Domain\Validator\DeviceId\IsDeviceIdNotExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class IsDeviceIdNotExistsValidatorTest extends ValidatorTestCase
{
    private IsDeviceIdNotExists $givenConstraint;
    protected DeviceRepositoryInterface $deviceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $deviceRepository = $this->container->get(DeviceRepositoryInterface::class);
        Assert::assertInstanceOf(DeviceRepositoryInterface::class, $deviceRepository);
        $this->deviceRepository = $deviceRepository;

        $this->givenConstraint = new IsDeviceIdNotExists();
    }

    protected function createValidator(): IsDeviceIdNotExistsValidator
    {
        /* @var IsDeviceIdNotExistsValidator */
        return $this->container->get(IsDeviceIdNotExistsValidator::class);
    }

    /** @test */
    public function deviceIdExistsInDatabase()
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
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingId->toRfc4122())
            ->setCode('403')
            ->assertRaised();
    }

    /** @test */
    public function deviceIdNotExistsInDatabase()
    {
        // given
        $givenNonExistingId = Uuid::v4();

        // when
        $this->validator->validate($givenNonExistingId, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenExistingId = Uuid::v4();
        $givenDevice = DeviceBuilder::any()
            ->withId($givenExistingId)
            ->build();
        $this->deviceRepository->save($givenDevice);

        $givenViolationCode = '123';

        // when
        $givenConstraint = new IsDeviceIdNotExists($givenViolationCode);
        $this->validator->validate($givenExistingId, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingId->toRfc4122())
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
