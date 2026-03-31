<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Request;
use Wexample\PhpApi\Common\AbstractApiRepository;

class RequestRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Request::class;
    }
}
