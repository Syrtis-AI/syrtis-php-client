<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

class Session extends AbstractApiEntity
{
    public function __construct(
        string $secureId,
        protected string $title,
    ) {
        parent::__construct(
            secureId: $secureId
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            secureId: (string) $data['secureId'],
            title: (string) $data['title'],
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
