<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Domain\Client\Looko2ApiClientInterface;
use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class Looko2ApiClient implements Looko2ApiClientInterface
{
    public function __construct(
        private string $baseUrl,
        private HttpClientInterface $httpClient,
    ) {
    }

    public function getLastDeviceMeasurement(string $externalDeviceId, string $token): array
    {
        $response = $this->httpClient->request(
            method: 'GET',
            url: sprintf('%s?method=GetLOOKO&id=%s&token=%s', $this->baseUrl, $externalDeviceId, $token)
        );

        $decodedResponse = json_decode($response->getContent(), true);

        if (is_null($decodedResponse)) {
            throw new RuntimeException(sprintf('There is problem with downloading data form Looko2. Device externalId: "%s". ', $externalDeviceId));
        }

        return $decodedResponse;
    }
}
