<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\Longitude;

use App\Domain\Validator\Longitude\IsCorrectLongitude;
use App\Domain\Validator\Longitude\IsCorrectLongitudeValidator;
use App\Tests\Common\ValidatorTestCase;

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
        /** @var IsCorrectLongitudeValidator */
        return $this->container->get(IsCorrectLongitudeValidator::class);
    }

    /** @test */
    public function longitude_value_is_lower_than_correct()
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

    /** @test */
    public function longitude_value_is_higher_than_correct()
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

    /** @test */
    public function longitude_value_is_on_top_edge()
    {
        // given
        $givenTopEdgeLongitudeValue = 180;

        // when
        $this->validator->validate($givenTopEdgeLongitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function longitude_value_is_zero()
    {
        // given
        $givenZeroLongitudeValue = 0;

        // when
        $this->validator->validate($givenZeroLongitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function longitude_value_is_on_bottom_edge()
    {
        // given
        $givenTopEdgeLongitudeValue = -180;

        // when
        $this->validator->validate($givenTopEdgeLongitudeValue, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validator_sets_given_validation_code()
    {
        // given
        $givenIncorrectLongitudeValue = -181;
        $givenViolationCode = '123';

        // when
        $givenConstraint = new IsCorrectLongitude ($givenViolationCode);
        $this->validator->validate($givenIncorrectLongitudeValue, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', strval($givenIncorrectLongitudeValue))
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}