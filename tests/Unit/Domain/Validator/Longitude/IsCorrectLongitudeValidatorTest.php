<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\Longitude;

use App\Domain\Validator\Longitude\IsCorrectLongitude;
use App\Domain\Validator\Longitude\IsCorrectLongitudeValidator;
use App\Tests\Common\ValidatorTestCase;
use PHPUnit\Framework\Attributes\Test;

final class IsCorrectLongitudeValidatorTest extends ValidatorTestCase
{
    private IsCorrectLongitude $givenConstraint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenConstraint = new IsCorrectLongitude();
    }

    protected function createValidator(): IsCorrectLongitudeValidator
    {
        return new IsCorrectLongitudeValidator();
    }

    #[Test]
    public function longitudeValueIsLowerThanCorrect()
    {
        // given
        $givenToLowLongitudeValue = -181;

        // when
        $this->validator->validate($givenToLowLongitudeValue, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', strval($givenToLowLongitudeValue))
            ->setCode('400')
            ->assertRaised();
    }

    #[Test]
    public function longitudeValueIsHigherThanCorrect()
    {
        // given
        $givenToHighLongitudeValue = 181;

        // when
        $this->validator->validate($givenToHighLongitudeValue, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', strval($givenToHighLongitudeValue))
            ->setCode('400')
            ->assertRaised();
    }

    #[Test]
    public function longitudeValueIsOnTopEdge()
    {
        // given
        $givenTopEdgeLongitudeValue = 180;

        // when
        $this->validator->validate($givenTopEdgeLongitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    #[Test]
    public function longitudeValueIsZero()
    {
        // given
        $givenZeroLongitudeValue = 0;

        // when
        $this->validator->validate($givenZeroLongitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    #[Test]
    public function longitudeValueIsOnBottomEdge()
    {
        // given
        $givenTopEdgeLongitudeValue = -180;

        // when
        $this->validator->validate($givenTopEdgeLongitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    #[Test]
    public function validatorSetsGivenValidationCode()
    {
        // given
        $givenIncorrectLongitudeValue = -181;
        $givenViolationCode = '123';

        // when
        $givenConstraint = new IsCorrectLongitude($givenViolationCode);
        $this->validator->validate($givenIncorrectLongitudeValue, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', strval($givenIncorrectLongitudeValue))
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}
