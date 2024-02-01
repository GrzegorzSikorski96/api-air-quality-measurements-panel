<?php

declare(strict_types=1);

namespace App\Domain\ReadModel\MeasurementParameter;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use App\Infrastructure\Messenger\Query\QueryFinderInterface;

final class MeasurementParameterFinder implements QueryFinderInterface
{
    public function __construct(
        private MeasurementParameterRepositoryInterface $measurementParameterRepository
    ) {
    }

    public function __invoke(MeasurementParameterQuery $query): MeasurementParameterPresenter
    {
        return new MeasurementParameterPresenter($this->measurementParameterRepository->findOne($query->id));
    }
}
