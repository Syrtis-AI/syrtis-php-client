<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Template;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class TemplateRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Template::class;
    }
}
