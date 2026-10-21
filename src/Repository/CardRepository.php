<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Card;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class CardRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Card::class;
    }
}
