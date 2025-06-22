<?php

declare(strict_types=1);

namespace App\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class KernelTestCaseAccessor extends KernelTestCase
{
    public static function init(): ContainerInterface
    {
        self::bootKernel();
        return self::getContainer();
    }
}
