<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Mother\ApiResponse;

final class Looko2ApiResponseMother
{
    public static function getExampleResponse(): string
    {
        return file_get_contents(__DIR__.'/../resources/looko2_response.json');
    }
}
