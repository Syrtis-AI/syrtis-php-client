<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\SlotConfig;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class SlotConfigRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return SlotConfig::class;
    }
}
