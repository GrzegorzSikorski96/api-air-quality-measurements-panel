<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\ApiProvider;

use App\Domain\Entity\Enum\ApiProviderEnum;
use App\Domain\Validator\ApiProvider\IsOneOfApiProviders;
use App\Domain\Validator\ApiProvider\IsOneOfApiProvidersValidator;
use App\Tests\Common\ValidatorTestCase;

final class IsOneOfApiProvidersValidatorTest extends ValidatorTestCase
{
    private IsOneOfApiProviders $givenConstraint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenConstraint = new IsOneOfApiProviders();
    }

    protected function createValidator(): IsOneOfApiProvidersValidator
    {
        /** @var IsOneOfApiProvidersValidator */
        return $this->container->get(IsOneOfApiProvidersValidator::class);
    }

    /** @test */
    public function api_provider_does_not_exists_in_enum()
    {
        // given
        $givenNotExistingApiProvider = 'NonExistingApiProvider';

        // when
        $this->validator->validate($givenNotExistingApiProvider, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNotExistingApiProvider)
            ->setCode('400')
            ->assertRaised();
    }

    /** @test */
    public function api_provider_already_exists_in_enum()
    {
        // given
        $givenApiProvider = ApiProviderEnum::LOOKO2->value;

        // when
        $this->validator->validate($givenApiProvider, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validator_sets_given_validation_code()
    {
        // given
        $givenViolationCode = '123';
        $givenNotExistingApiProvider = 'NonExistingApiProvider';

        // when
        $givenConstraint = new IsOneOfApiProviders($givenViolationCode);
        $this->validator->validate($givenNotExistingApiProvider, $givenConstraint);

        // then
        $this->buildViolation($givenConstraint->message)
            ->setParameter('{{ string }}', $givenNotExistingApiProvider)
            ->setCode($givenViolationCode)
            ->assertRaised();
    }
}