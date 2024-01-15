<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Repository\DeviceRepositoryInterface;
use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Tests\Doubles\Repository\DeviceInMemoryRepository;
use App\Tests\Doubles\Repository\MeasurementParameterInMemoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

abstract class ValidatorTestCase extends ConstraintValidatorTestCase
{
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
        $this->container->set(MeasurementParameterRepositoryInterface::class, new MeasurementParameterInMemoryRepository());
        $this->container->set(DeviceRepositoryInterface::class, new DeviceInMemoryRepository());

        parent::setUp();
    }
}