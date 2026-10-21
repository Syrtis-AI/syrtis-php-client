<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\SlotRelation;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class SlotRelationRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return SlotRelation::class;
    }
}
