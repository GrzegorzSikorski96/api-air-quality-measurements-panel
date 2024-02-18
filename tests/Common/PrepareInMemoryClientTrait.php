<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Client\Looko2ApiClientInterface;
use App\Tests\Doubles\ApiClient\HttpResponse;
use App\Tests\Doubles\ApiClient\Looko2ApiClientInMemory;

trait PrepareInMemoryClientTrait
{
    private function substituteClientInMemoryImplementation(): void
    {
        $this->container->set(Looko2ApiClientInterface::class, new Looko2ApiClientInMemory(new HttpResponse()));
    }
}
