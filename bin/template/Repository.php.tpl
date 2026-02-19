<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\{{CLASS_NAME}};
use Wexample\PhpApi\Common\AbstractApiRepository;

class {{CLASS_NAME}}Repository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return {{CLASS_NAME}}::class;
    }
}
