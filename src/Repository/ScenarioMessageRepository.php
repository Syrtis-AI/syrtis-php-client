<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\ScenarioMessage;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class ScenarioMessageRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return ScenarioMessage::class;
    }
}
