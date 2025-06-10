<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller\Api\V1;

use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterControllerTest extends UnitTestCase
{
    /** @test */
    public function allMeasurementParameters()
    {
        // given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->measurementParameterRepository->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->measurementParameterRepository->save($givenSecondMeasurementParameter);

        // when
        $response = $this->selfRequest('GET', '/api/v1/measurementParameters');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $measurementParameters = json_decode($response->getContent());

        foreach ($measurementParameters as $measurementParameter) {
            if ($measurementParameter->id === $givenFirstMeasurementParameter->getId()->toRfc4122()) {
                $expectedMeasurementParameter = $givenFirstMeasurementParameter;
            } elseif ($measurementParameter->id === $givenSecondMeasurementParameter->getId()->toRfc4122()) {
                $expectedMeasurementParameter = $givenSecondMeasurementParameter;
            }

            Assert::assertEquals($expectedMeasurementParameter->getId(), $measurementParameter->id);
            Assert::assertEquals($expectedMeasurementParameter->getName(), $measurementParameter->name);
            Assert::assertEquals($expectedMeasurementParameter->getCode(), $measurementParameter->code);
            Assert::assertEquals($expectedMeasurementParameter->getFormula(), $measurementParameter->formula);
        }
    }

    /** @test */
    public function existingMeasurementParameter()
    {
        // given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()
        ->withId(Uuid::fromString('43192d2a-724e-4e43-b5bd-ec0588b38c53'))
        ->build();
        $this->measurementParameterRepository->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()
        ->withId(Uuid::v4())
        ->build();
        $this->measurementParameterRepository->save($givenSecondMeasurementParameter);

        // when
        $response = $this->selfRequest('GET', '/api/v1/measurementParameters/43192d2a-724e-4e43-b5bd-ec0588b38c53');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $measurementParameter = json_decode($response->getContent());

        Assert::assertEquals($givenFirstMeasurementParameter->getId(), $measurementParameter->id);
        Assert::assertEquals($givenFirstMeasurementParameter->getName(), $measurementParameter->name);
        Assert::assertEquals($givenFirstMeasurementParameter->getCode(), $measurementParameter->code);
        Assert::assertEquals($givenFirstMeasurementParameter->getFormula(), $measurementParameter->formula);
    }

    /** @test */
    public function notExistingMeasurementParameter()
    {
        // given

        // when
        $response = $this->selfRequest('GET', '/api/v1/measurementParameters/43192d2a-724e-4e43-b5bd-ec0588b38c53');

        // then
        Assert::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
