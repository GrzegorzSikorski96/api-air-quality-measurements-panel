<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\MeasurementParameter;

use App\Infrastructure\Messenger\Query\QueryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class MeasurementParameterQuery implements QueryInterface
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
