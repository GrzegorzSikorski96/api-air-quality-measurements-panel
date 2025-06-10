<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use LogicException;

final class NonExistentEntityException extends LogicException
{
    public function __construct(string $entityName, string $entityId)
    {
        parent::__construct(
            sprintf("Entity: '%s' with id: '%s' does not exist.", $entityName, $entityId)
        );
    }
}
