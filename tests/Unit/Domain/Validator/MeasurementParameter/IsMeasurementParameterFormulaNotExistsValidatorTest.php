<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\MeasurementParameter;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Validator\MeasurementParameterFormula\IsMeasurementParameterFormulaNotExists;
use App\Domain\Validator\MeasurementParameterFormula\IsMeasurementParameterFormulaNotExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;

final class IsMeasurementParameterFormulaNotExistsValidatorTest extends ValidatorTestCase
{
    private IsMeasurementParameterFormulaNotExists $givenConstraint;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $this->givenConstraint = new IsMeasurementParameterFormulaNotExists();
    }

    protected function createValidator(): IsMeasurementParameterFormulaNotExistsValidator
    {
        /** @var IsMeasurementParameterFormulaNotExistsValidator */
        return $this->container->get(IsMeasurementParameterFormulaNotExistsValidator::class);
    }

    /** @test */
    public function measurement_parameter_formula_does_not_exists_in_database()
    {
        // given
        $givenNonExistingMeasurementParameterFormula = "Param";

        // when
        $this->validator->validate($givenNonExistingMeasurementParameterFormula, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function measurement_parameter_formula_already_exists_in_database()
    {
        // given
        $givenExistingFormula = 'Parameter';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withFormula($givenExistingFormula)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $this->validator->validate($givenExistingFormula, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingFormula)
            ->setCode('403')
            ->assertRaised();
    }

    /** @test */
    public function validator_sets_given_validation_code()
    {
        // given
        $givenViolationCode = '123';
        $givenExistingFormula = 'Parameter';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withFormula($givenExistingFormula)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $givenConstraint = new IsMeasurementParameterFormulaNotExists($givenViolationCode);
        $this->validator->validate($givenExistingFormula, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingFormula)
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}