<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Session;
use SyrtisClient\Repository\AbstractSyrtisRepository;

class SessionRepository extends AbstractSyrtisRepository
{
    public static function getEntityType(): string
    {
        return Session::class;
    }

}
