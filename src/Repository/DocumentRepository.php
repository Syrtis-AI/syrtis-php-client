<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Document;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class DocumentRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Document::class;
    }
}
