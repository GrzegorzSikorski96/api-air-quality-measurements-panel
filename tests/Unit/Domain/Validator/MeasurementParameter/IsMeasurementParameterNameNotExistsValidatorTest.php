<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\MeasurementParameter;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Validator\MeasurementParameterName\IsMeasurementParameterNameNotExists;
use App\Domain\Validator\MeasurementParameterName\IsMeasurementParameterNameNotExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;

final class IsMeasurementParameterNameNotExistsValidatorTest extends ValidatorTestCase
{
    private IsMeasurementParameterNameNotExists $givenConstraint;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $this->givenConstraint = new IsMeasurementParameterNameNotExists();
    }

    protected function createValidator(): IsMeasurementParameterNameNotExistsValidator
    {
        /* @var IsMeasurementParameterNameNotExistsValidator */
        return $this->container->get(IsMeasurementParameterNameNotExistsValidator::class);
    }

    #[Test]
    public function measurementParameterNameDoesNotExistsInDatabase()
    {
        // given
        $givenNonExistingMeasurementParameterName = 'Param';

        // when
        $this->validator->validate($givenNonExistingMeasurementParameterName, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    #[Test]
    public function measurementParameterNameAlreadyExistsInDatabase()
    {
        // given
        $givenExistingName = 'Parameter';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName($givenExistingName)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $this->validator->validate($givenExistingName, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingName)
            ->setCode('403')
            ->assertRaised();
    }

    #[Test]
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenViolationCode = '123';
        $givenExistingName = 'Parameter';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withName($givenExistingName)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $givenConstraint = new IsMeasurementParameterNameNotExists($givenViolationCode);
        $this->validator->validate($givenExistingName, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingName)
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
