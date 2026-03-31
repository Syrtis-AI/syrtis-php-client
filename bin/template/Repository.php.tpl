<?php

declare(strict_types=1);

namespace {{REPOSITORY_NAMESPACE}};

use {{ENTITY_NAMESPACE}}\{{CLASS_NAME}};
use Wexample\PhpApi\Common\AbstractApiRepository;

class {{CLASS_NAME}}Repository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return {{CLASS_NAME}}::class;
    }
}
