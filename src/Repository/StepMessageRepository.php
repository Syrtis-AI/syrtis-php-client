<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\StepMessage;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class StepMessageRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return StepMessage::class;
    }
}
