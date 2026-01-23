<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

class Scenario extends AbstractApiEntity
{
    public static function getEntityName(): string
    {
        return 'scenario';
    }

    public function __construct(
        string $secureId,
        // TODO Temp polyfill id, will be removed at v1.0.0
        protected int $id,
        protected string $title,
    ) {
        parent::__construct(
            secureId: $secureId,
        );
    }

    public static function fromArray(array $data): static
    {
        return new self(
            id: (int) $data['id'],// TODO Temp polyfill id, will be removed at v1.0.0
            secureId: (string) $data['secureId'],
            title: (string) $data['title'],
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
