<?php

declare(strict_types=1);

namespace App\Tests\Doubles\ApiClient;

final class HttpResponse
{
    public array $responses = [];

    public function getResponse(): string
    {
        return array_shift($this->responses);
    }

    public function setResponsesList(array $responses): void
    {
        $this->responses = $responses;
    }
}
