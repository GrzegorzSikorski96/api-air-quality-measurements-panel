<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\V1;

use App\Domain\DTO\Request\MeasurementsRequestQueryDTO;
use App\Domain\ReadModel\Measurements\MeasurementsQuery;
use App\Infrastructure\Messenger\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class MeasurementController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {
    }

    #[Route(path: '/measurements', name: 'measurements')]
    public function measurements(
        #[MapQueryString] MeasurementsRequestQueryDTO $measurementsRequestsQuery,
    ): JsonResponse {
        $measurementsQuery = new MeasurementsQuery(
            deviceId: $measurementsRequestsQuery->deviceId,
            measurementParameterId: $measurementsRequestsQuery->measurementParameterId,
            startDateTime: $measurementsRequestsQuery->startDateTime,
            endDateTime: $measurementsRequestsQuery->endDateTime
        );

        $measurements = $this->queryBus->find($measurementsQuery);

        return new JsonResponse($measurements->measurements);
    }
}
