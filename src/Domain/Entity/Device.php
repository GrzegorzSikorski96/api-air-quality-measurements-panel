<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\Enum\ApiProviderEnum;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
final class Device
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $name;

    #[ORM\Column(type: Types::FLOAT)]
    private float $latitude;

    #[ORM\Column(type: Types::FLOAT)]
    private float $longitude;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $externalId;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $token;

    #[ORM\Column(type: Types::STRING, enumType: ApiProviderEnum::class)]
    private ApiProviderEnum $provider;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    public function __construct(string $name, float $latitude, float $longitude, ApiProviderEnum $provider, string $externalId = null, string $token = null, ?Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->provider = $provider;

        $this->externalId = $externalId;
        $this->token = $token;

        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId = null): void
    {
        $this->externalId = $externalId;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token = null): void
    {
        $this->token = $token;
    }

    public function getProvider(): ApiProviderEnum
    {
        return $this->provider;
    }

    public function setProvider(ApiProviderEnum $provider): void
    {
        $this->provider = $provider;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}