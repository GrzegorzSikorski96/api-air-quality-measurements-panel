<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[UniqueEntity(
    fields: ['deviceId', 'measurementParameterId'],
    errorPath: 'measurementParameterId',
    message: 'This parameter is already assigned to this device.',
)]
#[UniqueConstraint(columns: ['device_id', 'measurement_parameter_id'])]
class DeviceMeasurementParameter
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $deviceId;

    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $measurementParameterId;

    public function __construct(Uuid $deviceId, Uuid $measurementParameterId, Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
        $this->deviceId = $deviceId;
        $this->measurementParameterId = $measurementParameterId;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getDeviceId(): Uuid
    {
        return $this->deviceId;
    }

    public function getMeasurementParameterId(): Uuid
    {
        return $this->measurementParameterId;
    }
}
