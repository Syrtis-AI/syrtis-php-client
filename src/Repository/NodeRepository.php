<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Node;
use Wexample\PhpApiEntity\Common\AbstractApiRepository;

class NodeRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Node::class;
    }
}
