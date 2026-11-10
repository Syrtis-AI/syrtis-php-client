<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Step;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class StepRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Step::class;
    }
}
