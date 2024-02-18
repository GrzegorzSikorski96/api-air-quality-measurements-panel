<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UI\Controller\Api\V1;

use App\Tests\Common\AcceptanceTestCase;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

final class HealthCheckControllerTest extends AcceptanceTestCase
{
    /** @test */
    public function health()
    {
        // given

        // when
        $response = $this->selfRequest('GET', '/api/v1/health');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $health = json_decode($response->getContent());

        Assert::assertObjectHasProperty('api', $health);
        Assert::assertObjectHasProperty('database', $health);
    }
}
