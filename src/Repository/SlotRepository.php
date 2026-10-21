<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Slot;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class SlotRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Slot::class;
    }
}
