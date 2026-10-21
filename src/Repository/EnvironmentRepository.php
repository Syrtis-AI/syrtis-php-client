<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Environment;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class EnvironmentRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Environment::class;
    }
}
