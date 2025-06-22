<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\MeasurementParameter;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Messenger\Query\QueryFinderInterface;

final readonly class MeasurementParameterFinder implements QueryFinderInterface
{
    public function __construct(
        private MeasurementParameterRepositoryInterface $measurementParameterRepository,
    ) {
    }

    public function __invoke(MeasurementParameterQuery $query): ?MeasurementParameterPresenter
    {
        $measurementParameter = $this->measurementParameterRepository->findOne($query->id);

        if (! $measurementParameter) {
            return null;
        }

        return new MeasurementParameterPresenter($measurementParameter);
    }
}
