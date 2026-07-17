<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

use Wexample\Helpers\Helper\ClassHelper;

abstract class AbstractApiEntity extends \Wexample\PhpApi\Common\AbstractApiEntity
{
    /**
     * Entity names follow the API wire contract, which is camelCase
     * throughout — an item's "type" included (UserConfig -> "userConfig").
     */
    public static function getEntityName(): string
    {
        return ClassHelper::getFieldName(static::class);
    }
}
