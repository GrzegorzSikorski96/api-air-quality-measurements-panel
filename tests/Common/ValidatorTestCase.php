<?php

declare(strict_types=1);

namespace App\Tests\Common;

use Symfony\Component\HttpKernel\KernelInterface;
use App\Domain\Repository\DeviceRepositoryInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Domain\Repository\MeasurementRepositoryInterface;
use App\Tests\Doubles\Repository\DeviceInMemoryRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Tests\Doubles\Repository\MeasurementInMemoryRepository;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Tests\Doubles\Repository\MeasurementParameterInMemoryRepository;
use App\Tests\Doubles\Repository\DeviceMeasurementParameterInMemoryRepository;

abstract class ValidatorTestCase extends ConstraintValidatorTestCase
{
    use PrepareInMemoryRepositoryTrait;

    protected ContainerInterface $container;
    protected MeasurementParameterRepositoryInterface $measurementParameterRepository;

    protected function setUp(): void
    {
        $kernelTestCase = new class extends KernelTestCase{
            public static function getContainer(): Container
            {
                return parent::getContainer();
            }
            public static function bootKernel(array $options = []): KernelInterface
            {
                return parent::bootKernel();
            }
        };
        $kernelTestCase::bootKernel();

        $this->container = $kernelTestCase::getContainer();

        $this->substituteRepositoryInMemoryImplementation();
        
        parent::setUp();
    }
}