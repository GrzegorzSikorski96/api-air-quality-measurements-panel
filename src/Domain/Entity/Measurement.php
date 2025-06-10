<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Measurement
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $measurementParameterId;

    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $deviceId;

    #[ORM\Column(type: Types::FLOAT)]
    private float $value;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $recordedAt;

    public function __construct(Uuid $measurementParameterId, Uuid $deviceId, float $value, DateTimeImmutable $recordedAt, ?Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
        $this->measurementParameterId = $measurementParameterId;
        $this->deviceId = $deviceId;
        $this->value = $value;
        $this->recordedAt = $recordedAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getMeasurementParameterId(): Uuid
    {
        return $this->measurementParameterId;
    }

    public function setMeasurementParameterId(Uuid $measurementParameterId): void
    {
        $this->measurementParameterId = $measurementParameterId;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getDeviceId(): Uuid
    {
        return $this->deviceId;
    }

    public function setDeviceId(Uuid $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    public function getRecordedAt(): DateTimeImmutable
    {
        return $this->recordedAt;
    }

    public function setRecordedAt(DateTimeImmutable $recordedAt): void
    {
        $this->recordedAt = $recordedAt;
    }
}
