<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\V1;

use App\Domain\Repository\MeasurementParameterRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthCheckController extends AbstractController
{
    public function __construct(
        private readonly MeasurementParameterRepositoryInterface $measurementParameterRepository
    ) {
    }

    #[Route(path: '/health', name: 'application_healthcheck')]
    public function index(): JsonResponse
    {
        $status = Response::HTTP_OK;
        $checks = [
            'api' => 'ok',
            'database' => 'ok',
        ];

        try {
            $this->measurementParameterRepository->findAll();
        } catch (\Exception) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $checks['database'] = 'not ok';
        }

        return new JsonResponse($checks, $status);
    }
}
