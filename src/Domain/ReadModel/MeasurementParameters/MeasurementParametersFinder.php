<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\MeasurementParameters;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Messenger\Query\QueryFinderInterface;

final class MeasurementParametersFinder implements QueryFinderInterface
{
    public function __construct(
        private MeasurementParameterRepositoryInterface $measurementParameterRepository
    ) {
    }

    public function __invoke(MeasurementParametersQuery $query): MeasurementParametersPresenter
    {
        return new MeasurementParametersPresenter($this->measurementParameterRepository->findAll());
    }
}
