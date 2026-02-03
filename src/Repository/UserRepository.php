<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\User;
use SyrtisClient\Repository\AbstractSyrtisRepository;

class UserRepository extends AbstractSyrtisRepository
{
    public static function getEntityType(): string
    {
        return User::class;
    }
}
