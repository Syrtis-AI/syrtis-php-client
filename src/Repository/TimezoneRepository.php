<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Timezone;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class TimezoneRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Timezone::class;
    }
}
