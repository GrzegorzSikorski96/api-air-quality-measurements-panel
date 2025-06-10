<?php

declare(strict_types=1);

namespace App\Tests\Doubles\ApiClient;

use App\Domain\Client\Looko2ApiClientInterface;
use App\Tests\Fixtures\Mother\ApiResponse\Looko2ApiResponseMother;

final readonly class Looko2ApiClientInMemory implements Looko2ApiClientInterface
{
    public function __construct(
        private HttpResponse $httpResponse,
    ) {
        $this->httpResponse->setResponsesList([Looko2ApiResponseMother::getExampleResponse()]);
    }

    public function getLastDeviceMeasurement(string $externalDeviceId, string $token): array
    {
        $response = $this->httpResponse->getResponse();

        return json_decode($response, true);
    }
}
