<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\User;
use Wexample\PhpApi\Common\AbstractApiRepository;

class UserRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return User::class;
    }
}
