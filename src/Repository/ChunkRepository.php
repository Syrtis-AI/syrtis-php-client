<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Chunk;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class ChunkRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Chunk::class;
    }
}
