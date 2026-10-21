<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\SlotMessage;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class SlotMessageRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return SlotMessage::class;
    }
}
