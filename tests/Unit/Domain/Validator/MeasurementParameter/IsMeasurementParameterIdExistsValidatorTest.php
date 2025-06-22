<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\MeasurementParameter;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Validator\MeasurementParameterId\IsMeasurementParameterIdExists;
use App\Domain\Validator\MeasurementParameterId\IsMeasurementParameterIdExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

final class IsMeasurementParameterIdExistsValidatorTest extends ValidatorTestCase
{
    private IsMeasurementParameterIdExists $givenConstraint;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $this->givenConstraint = new IsMeasurementParameterIdExists();
    }

    protected function createValidator(): IsMeasurementParameterIdExistsValidator
    {
        /* @var IsMeasurementParameterIdExistsValidator */
        return $this->container->get(IsMeasurementParameterIdExistsValidator::class);
    }

    #[Test]
    public function measurementParameterIdDoesNotExistsInDatabase()
    {
        // given
        $givenNotExistingMeasurementParameterId = Uuid::v4();

        // when
        $this->validator->validate($givenNotExistingMeasurementParameterId, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNotExistingMeasurementParameterId->toRfc4122())
            ->setCode('404')
            ->assertRaised();
    }

    #[Test]
    public function measurementParameterIdAlreadyExistsInDatabase()
    {
        // given
        $givenExistingId = Uuid::v4();
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withId($givenExistingId)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

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
        $givenConstraint = new IsMeasurementParameterIdExists($givenViolationCode);
        $this->validator->validate($givenNotExistingId, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenNotExistingId->toRfc4122())
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
