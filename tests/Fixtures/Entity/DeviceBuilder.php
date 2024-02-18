<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Entity;

use App\Domain\Entity\Device;
use App\Domain\Entity\Enum\ApiProviderEnum;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

final class DeviceBuilder
{
    private Uuid $id;
    private string $name;
    private float $latitude;
    private float $longitude;
    private ?string $externalId = null;
    private ?string $token = null;
    private ApiProviderEnum $provider;

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

    public function withLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function withLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function withExternalId(string $externalId = null): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function withToken(string $token = null): self
    {
        $this->token = $token;

        return $this;
    }

    public function withProvider(ApiProviderEnum $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public static function any(): self
    {
        return new DeviceBuilder();
    }

    public function build(): Device
    {
        $faker = Factory::create();

        return new Device(
            name: $this->name ?? $faker->city(),
            latitude: $this->latitude ?? $faker->latitude(),
            longitude: $this->longitude ?? $faker->longitude(),
            provider: $this->provider ?? ApiProviderEnum::LOOKO2,
            externalId: $this->externalId,
            token: $this->token,
            id: $this->id ?? Uuid::v4()
        );
    }
}
