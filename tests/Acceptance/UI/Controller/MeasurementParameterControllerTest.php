<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UI\Controller;

use PHPUnit\Framework\Assert;
use App\Tests\Asserts\UuidAssert;
use App\Tests\Common\AcceptanceTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterControllerTest extends AcceptanceTestCase
{
    /** @test */
    public function all_measurement_parameters()
    {
        // given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->handleCreateMeasurementParameter($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->handleCreateMeasurementParameter($givenSecondMeasurementParameter);
        
        $givenThirdMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->handleCreateMeasurementParameter($givenThirdMeasurementParameter);

        // when
        $content = $this->selfRequest('GET', '/measurementParameters')->getContent();

        // then
        Assert::isJson($content);
        $content = json_decode($content);
        
        foreach($content as $item) {
            Assert::assertObjectHasProperty('id', $item);
            UuidAssert::assertUuid($item->id);

            Assert::assertObjectHasProperty('name', $item);
            Assert::assertObjectHasProperty('code', $item);
            Assert::assertObjectHasProperty('formula', $item);
        }
    }

    /** @test */
    public function measurement_parameter()
    {
        // given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()
        ->withId(Uuid::fromString('43192d2a-724e-4e43-b5bd-ec0588b38c53'))
        ->build();
        $this->handleCreateMeasurementParameter($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()
        ->withId(Uuid::v4())
        ->build();
        $this->handleCreateMeasurementParameter($givenSecondMeasurementParameter);

        // when
        $content = $this->selfRequest('GET', '/measurementParameter/43192d2a-724e-4e43-b5bd-ec0588b38c53')->getContent();

        // then
        Assert::isJson($content);
        $content = json_decode($content);

        Assert::assertObjectHasProperty('id', $content);
        UuidAssert::assertUuid($content->id);

        Assert::assertObjectHasProperty('name', $content);
        Assert::assertObjectHasProperty('code', $content);
        Assert::assertObjectHasProperty('formula', $content);
    }
}