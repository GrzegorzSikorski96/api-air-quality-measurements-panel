<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Entity;

use App\Domain\Entity\MeasurementParameter;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterBuilder
{
    private Uuid $id;
    private string $name;
    private string $code;
    private string $formula;

    public function withId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function withFormula(string $formula): self
    {
        $this->formula = $formula;

        return $this;
    }

    public static function any(): self
    {
        return new MeasurementParameterBuilder();
    }

    public function build(): MeasurementParameter
    {
        $faker = Factory::create();

        return new MeasurementParameter(
            name: $this->name ?? $faker->city(),
            code: $this->code ?? $faker->postcode(),
            formula: $this->formula ?? $faker->hexColor(),
            id: $this->id ?? Uuid::v4()
        );
    }
}
