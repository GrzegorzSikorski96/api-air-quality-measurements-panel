<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Enum;

use App\Domain\Entity\Enum\ApiProviderEnum;
use App\Tests\Common\UnitTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;

final class ApiProviderEnumTest extends UnitTestCase
{
    #[Test]
    public function getAllApiProviders()
    {
        // given

        // when
        $allApiProviders = ApiProviderEnum::getAllApiProviders();

        // then
        Assert::assertIsArray($allApiProviders);
        Assert::assertCount(1, $allApiProviders);
    }
}
