<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller;

use PHPUnit\Framework\Assert;
use App\Tests\Common\UnitTestCase;
use Symfony\Component\HttpFoundation\Response;

final class HealthCheckControllerTest extends UnitTestCase
{    
    /** @test */
    public function health()
    {
        // given

        // when
        $response = $this->selfRequest('GET', '/health');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $health = json_decode($response->getContent());

        Assert::assertEquals('ok', $health->api);
        Assert::assertEquals('ok', $health->database);
    }
}