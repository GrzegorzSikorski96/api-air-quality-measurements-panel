<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\V1;

use App\Domain\ReadModel\Device\DeviceQuery;
use App\Domain\ReadModel\Devices\DevicesQuery;
use App\Domain\ReadModel\DeviceWithMeasurementParameters\DeviceWithMeasurementParametersQuery;
use App\Infrastructure\Messenger\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class DeviceController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {
    }

    #[Route(path: '/devices', name: 'devices')]
    public function devices(): JsonResponse
    {
        $allDevicesQuery = new DevicesQuery();
        $allDevices = $this->queryBus->find($allDevicesQuery);

        return new JsonResponse($allDevices->devices);
    }

    #[Route(path: '/device/{id}', name: 'device')]
    public function device(string $id): JsonResponse
    {
        $deviceQuery = new DeviceQuery(Uuid::fromString($id));
        $device = $this->queryBus->find($deviceQuery);

        if (is_null($device)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($device);
    }

    #[Route(path: '/device/{id}/details', name: 'device_details')]
    public function deviceDetails(string $id): JsonResponse
    {
        $deviceWithMeasurementParametersQuery = new DeviceWithMeasurementParametersQuery(Uuid::fromString($id));
        $deviceWithMeasurementParameters = $this->queryBus->find($deviceWithMeasurementParametersQuery);

        if (is_null($deviceWithMeasurementParameters)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($deviceWithMeasurementParameters);
    }
}
