<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Entity\MeasurementParameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $measurementParameters = $this->provideValues();

        foreach ($measurementParameters as $measurementParameter) {
            $parameter = new MeasurementParameter(
                name: $measurementParameter['name'],
                code: $measurementParameter['code'],
                formula: $measurementParameter['formula'],
                id: Uuid::fromString($measurementParameter['id'])
            );

            $manager->persist($parameter);
        }

        $manager->flush();
    }

    private function provideValues(): array
    {
        $valuesFile = file_get_contents(__DIR__.'/../../fixtures/measurement_parameters.json');

        return json_decode($valuesFile, true);
    }
}