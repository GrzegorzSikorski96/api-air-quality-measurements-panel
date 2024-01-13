<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Validator\MeasurementParameterUuid\IsMeasurementParameterUuidExists;
use App\Domain\Validator\MeasurementParameterUuid\IsMeasurementParameterUuidExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use App\Tests\Fixtures\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class IsMeasurementParameterUuidExistsValidatorTest extends ValidatorTestCase
{
    private IsMeasurementParameterUuidExists $givenConstraint;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $measurementParameterRepository = $this->container->get(MeasurementParameterRepositoryInterface::class);
        Assert::assertInstanceOf(MeasurementParameterRepositoryInterface::class, $measurementParameterRepository);
        $this->measurementParameterRepository = $measurementParameterRepository;

        $this->givenConstraint = new IsMeasurementParameterUuidExists();
    }

    protected function createValidator(): IsMeasurementParameterUuidExistsValidator
    {
        /** @var IsMeasurementParameterUuidExistsValidator */
        return $this->container->get(IsMeasurementParameterUuidExistsValidator::class);
    }

    /** @test */
    public function measurement_parameter_uuid_does_not_exists_in_database()
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

    /** @test */
    public function measurement_parameter_uuid_already_exists_in_database()
    {
        // given
        $givenExistingUuid = Uuid::v4();
        $givenMeasurementParameter = MeasurementParameterBuilder::any()
            ->withId($givenExistingUuid)
            ->build();

        $this->measurementParameterRepository->save($givenMeasurementParameter);

        // when
        $this->validator->validate($givenExistingUuid, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validator_sets_given_validation_code()
    {
        // given
        $givenViolationCode = '123';
        $givenNotExistingUuid = Uuid::v4();

        // when
        $givenConstraint = new IsMeasurementParameterUuidExists($givenViolationCode);
        $this->validator->validate($givenNotExistingUuid, $givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNotExistingUuid->toRfc4122())
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}