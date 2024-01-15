<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Enum;

use App\Domain\Entity\Enum\ApiProviderEnum;
use App\Tests\Common\UnitTestCase;
use PHPUnit\Framework\Assert;

final class ApiProviderEnumTest extends UnitTestCase
{
    /** @test */
    public function get_all_api_providers()
    {
        // given

        //when
        $allApiProviders = ApiProviderEnum::getAllApiProviders();

        //then
        Assert::assertIsArray($allApiProviders);
        Assert::assertCount(1, $allApiProviders);
    }
}
