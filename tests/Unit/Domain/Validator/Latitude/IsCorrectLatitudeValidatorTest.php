<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\Latitude;

use App\Domain\Validator\Latitude\IsCorrectLatitude;
use App\Domain\Validator\Latitude\IsCorrectLatitudeValidator;
use App\Tests\Common\ValidatorTestCase;

final class IsCorrectLatitudeValidatorTest extends ValidatorTestCase
{
    private IsCorrectLatitude $givenConstraint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenConstraint = new IsCorrectLatitude();
    }

    protected function createValidator(): IsCorrectLatitudeValidator
    {
        /* @var IsCorrectLatitudeValidator */
        return $this->container->get(IsCorrectLatitudeValidator::class);
    }

    /** @test */
    public function latitudeValueIsLowerThanCorrect()
    {
        // given
        $givenToLowLatitudeValue = -91;

        // when
        $this->validator->validate($givenToLowLatitudeValue, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', strval($givenToLowLatitudeValue))
            ->setCode('400')
            ->assertRaised();
    }

    /** @test */
    public function latitudeValueIsHigherThanCorrect()
    {
        // given
        $givenToHighLatitudeValue = 91;

        // when
        $this->validator->validate($givenToHighLatitudeValue, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', strval($givenToHighLatitudeValue))
            ->setCode('400')
            ->assertRaised();
    }

    /** @test */
    public function latitudeValueIsOnTopEdge()
    {
        // given
        $givenTopEdgeLatitudeValue = 90;

        // when
        $this->validator->validate($givenTopEdgeLatitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function latitudeValueIsZero()
    {
        // given
        $givenZeroLatitudeValue = 0;

        // when
        $this->validator->validate($givenZeroLatitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function latitudeValueIsOnBottomEdge()
    {
        // given
        $givenTopEdgeLatitudeValue = -90;

        // when
        $this->validator->validate($givenTopEdgeLatitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenIncorrectLatitudeValue = -91;
        $givenViolationCode = '123';

        // when
        $givenConstraint = new IsCorrectLatitude($givenViolationCode);
        $this->validator->validate($givenIncorrectLatitudeValue, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', strval($givenIncorrectLatitudeValue))
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
