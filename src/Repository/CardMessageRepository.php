<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\CardMessage;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class CardMessageRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return CardMessage::class;
    }
}
