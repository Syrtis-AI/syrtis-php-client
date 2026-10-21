<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\SlotMessageStamp;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class SlotMessageStampRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return SlotMessageStamp::class;
    }
}
