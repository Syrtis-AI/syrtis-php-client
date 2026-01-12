<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

use Wexample\Helpers\Class\Traits\HasSnakeShortClassNameClassTrait;
use Wexample\PhpApi\Common\AbstractApiEntity as BaseAbstractApiEntity;

abstract class AbstractApiEntity extends BaseAbstractApiEntity
{
    use HasSnakeShortClassNameClassTrait;

    public static function getEntityName(): string
    {
        return static::getSnakeShortClassName();
    }
}
