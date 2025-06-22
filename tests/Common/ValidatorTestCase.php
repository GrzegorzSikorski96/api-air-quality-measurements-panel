<?php

declare(strict_types=1);

namespace App\Tests\Common;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

abstract class ValidatorTestCase extends ConstraintValidatorTestCase
{
    use PrepareInMemoryRepositoryTrait;

    protected ContainerInterface $container;

    protected function setUp(): void
    {
        $this->container = KernelTestCaseAccessor::init();

        $this->substituteRepositoryInMemoryImplementation();

        parent::setUp();
    }
}
