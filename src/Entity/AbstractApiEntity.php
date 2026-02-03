<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

use Wexample\Helpers\Class\Traits\HasSnakeShortClassNameClassTrait;

abstract class AbstractApiEntity extends \Wexample\PhpApi\Common\AbstractApiEntity
{
    use HasSnakeShortClassNameClassTrait;

    public static function getEntityName(): string
    {
        return static::getSnakeShortClassName();
    }
}
