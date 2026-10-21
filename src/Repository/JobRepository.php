<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Job;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class JobRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Job::class;
    }
}
