<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\MeasurementParameter;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Validator\MeasurementParameterId\IsMeasurementParameterIdNotExists;
use App\Domain\Validator\MeasurementParameterId\IsMeasurementParameterIdNotExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class IsMeasurementParameterIdNotExistsValidatorTest extends ValidatorTestCase
{
    private IsMeasurementParameterIdNotExists $givenConstraint;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $this->givenConstraint = new IsMeasurementParameterIdNotExists();
    }

    protected function createValidator(): IsMeasurementParameterIdNotExistsValidator
    {
        /* @var IsMeasurementParameterIdNotExistsValidator */
        return $this->container->get(IsMeasurementParameterIdNotExistsValidator::class);
    }

    /** @test */
    public function measurementParameterIdExistsInDatabase()
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
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingId->toRfc4122())
            ->setCode('403')
            ->assertRaised();
    }

    /** @test */
    public function measurementParameterIdNotExistsInDatabase()
    {
        // given
        $givenNotExistingId = Uuid::v4();

        // when
        $this->validator->validate($givenNotExistingId, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenViolationCode = '123';
        $givenExistingId = Uuid::v4();
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withId($givenExistingId)
            ->build();
        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $givenConstraint = new IsMeasurementParameterIdNotExists($givenViolationCode);
        $this->validator->validate($givenExistingId, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingId->toRfc4122())
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
