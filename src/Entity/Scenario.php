<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

class Scenario extends AbstractApiEntity
{
    public static function getEntityName(): string
    {
        return 'scenario';
    }

    protected string $title = '';

    public function getTitle(): string
    {
        return $this->title;
    }
}
