<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Downloader;

use PHPUnit\Framework\Assert;
use App\Tests\Common\UnitTestCase;
use App\Domain\Downloader\DownloaderFactory;
use App\Domain\Entity\Enum\ApiProviderEnum;
use App\Infrastructure\Downloader\Looko2Downloader;
use App\Tests\Fixtures\Entity\DeviceBuilder;

final class DownloaderFactoryTest extends UnitTestCase
{
    private DownloaderFactory $downloaderFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $downloaderFactory = $this->container->get(DownloaderFactory::class);
        Assert::assertInstanceOf(DownloaderFactory::class, $downloaderFactory);
        $this->downloaderFactory = $downloaderFactory;
    }

    /** 
     * @test
     * @dataProvider apiProvidersProvider 
     */
    public function return_correct_downlaoder(string $givenApiProvider, string $expectedClass)
    {
        // given
        $device = DeviceBuilder::any()
        ->withProvider(ApiProviderEnum::tryFrom($givenApiProvider))
        ->build();

        // when
        $downloader = $this->downloaderFactory->create($device);

        // then
        Assert::assertInstanceOf($expectedClass, $downloader);
    }

    private function apiProvidersProvider(): array
    {
        return [
            [ApiProviderEnum::LOOKO2->value, Looko2Downloader::class]
        ];
    }
}