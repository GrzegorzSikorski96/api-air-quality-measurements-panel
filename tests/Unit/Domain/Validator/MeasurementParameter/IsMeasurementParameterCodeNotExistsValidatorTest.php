<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\MeasurementParameter;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Validator\MeasurementParameterCode\IsMeasurementParameterCodeNotExists;
use App\Domain\Validator\MeasurementParameterCode\IsMeasurementParameterCodeNotExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;

final class IsMeasurementParameterCodeNotExistsValidatorTest extends ValidatorTestCase
{
    private IsMeasurementParameterCodeNotExists $givenConstraint;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $this->givenConstraint = new IsMeasurementParameterCodeNotExists();
    }

    protected function createValidator(): IsMeasurementParameterCodeNotExistsValidator
    {
        /* @var IsMeasurementParameterCodeNotExistsValidator */
        return $this->container->get(IsMeasurementParameterCodeNotExistsValidator::class);
    }

    /** @test */
    public function measurementParameterCodeDoesNotExistsInDatabase()
    {
        // given
        $givenNonExistingMeasurementParameterCode = 'Param';

        // when
        $this->validator->validate($givenNonExistingMeasurementParameterCode, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function measurementParameterCodeAlreadyExistsInDatabase()
    {
        // given
        $givenExistingCode = 'Parameter';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withCode($givenExistingCode)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $this->validator->validate($givenExistingCode, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingCode)
            ->setCode('403')
            ->assertRaised();
    }

    /** @test */
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenViolationCode = '123';
        $givenExistingCode = 'Parameter';
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withCode($givenExistingCode)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $givenConstraint = new IsMeasurementParameterCodeNotExists($givenViolationCode);
        $this->validator->validate($givenExistingCode, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingCode)
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
