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
        // TODO Temp polyfill id, will be removed at v1.0.0
        protected ?int $id = null,
        string $secureId,
        protected string $title,
        protected string $projectSecureId,
    )
    {
        parent::__construct(
            secureId: $secureId,
        );
    }

    public static function fromArray(array $data): static
    {
        return new self(
            id: (isset($data['id']) ? (int) $data['id'] : null), // TODO Temp polyfill id, will be removed at v1.0.0
            secureId: (string) $data['secureId'],
            title: (string) $data['title'],
            projectSecureId: (string) $data['project'],
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @deprecated will be removed soon.
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getProjectSecureId(): string
    {
        return $this->projectSecureId;
    }
}
