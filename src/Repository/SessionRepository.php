<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Session;
use Wexample\PhpApi\Common\AbstractApiRepository;

class SessionRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Session::class;
    }

}
