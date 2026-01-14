<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Project;
use Wexample\PhpApi\Common\AbstractApiRepository;

class ProjectRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Project::class;
    }
}
