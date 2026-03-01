<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Message;
use Wexample\PhpApi\Common\AbstractApiRepository;

class MessageRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Message::class;
    }
}
