<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Domain\ReadModel\MeasurementParameter\MeasurementParameterQuery;
use App\Domain\ReadModel\MeasurementParameters\MeasurementParametersQuery;
use App\Infrastructure\Messenger\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class MeasurementParameterController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {
    }

    #[Route(path: '/measurementParameters', name: 'measurement_parameters')]
    public function measurementParameters(): JsonResponse
    {
        $allMeasurementParametersQuery = new MeasurementParametersQuery();
        $allMeasurementParameters = $this->queryBus->find($allMeasurementParametersQuery);

        return new JsonResponse($allMeasurementParameters->measurementParameters);
    }

    #[Route(path: '/measurementParameter/{id}', name: 'measurement_parameter')]
    public function measurementParameter(string $id): JsonResponse
    {
        $measurementParameterQuery = new MeasurementParameterQuery(Uuid::fromString($id));
        $measurementParameter = $this->queryBus->find($measurementParameterQuery);

        return new JsonResponse($measurementParameter);
    }
}
