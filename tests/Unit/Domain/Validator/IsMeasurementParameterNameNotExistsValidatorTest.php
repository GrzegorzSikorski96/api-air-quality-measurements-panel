<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Validator\MeasurementParameterName\IsMeasurementParameterNameNotExists;
use App\Domain\Validator\MeasurementParameterName\IsMeasurementParameterNameNotExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;

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
        /** @var IsMeasurementParameterNameNotExistsValidator */
        return $this->container->get(IsMeasurementParameterNameNotExistsValidator::class);
    }

    /** @test */
    public function measurement_parameter_name_does_not_exists_in_database()
    {
        // given
        $givenNonExistingMeasurementParameterName = "Param";

        // when
        $this->validator->validate($givenNonExistingMeasurementParameterName, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function measurement_parameter_name_already_exists_in_database()
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

    /** @test */
    public function validator_sets_given_validation_code()
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
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingName)
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}