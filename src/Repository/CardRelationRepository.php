<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\CardRelation;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class CardRelationRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return CardRelation::class;
    }
}
