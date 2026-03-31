<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\MessageStamp;
use Wexample\PhpApi\Common\AbstractApiRepository;

class MessageStampRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return MessageStamp::class;
    }
}
