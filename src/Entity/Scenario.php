<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

class Scenario extends AbstractApiEntity
{
    public static function getEntityName(): string
    {
        return 'scenario';
    }

    // TODO Temp polyfill id, will be removed at v1.0.0
    protected ?int $id = null;
    protected string $title = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @deprecated will be removed soon.
     */
    public function getId(): int
    {
        return (int) $this->id;
    }
}
