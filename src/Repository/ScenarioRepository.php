<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Scenario;
use Wexample\PhpApi\Common\AbstractApiRepository;

class ScenarioRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Scenario::class;
    }
}
