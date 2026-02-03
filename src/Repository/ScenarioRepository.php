<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Scenario;
use SyrtisClient\Repository\AbstractSyrtisRepository;

class ScenarioRepository extends AbstractSyrtisRepository
{
    public static function getEntityType(): string
    {
        return Scenario::class;
    }
}
