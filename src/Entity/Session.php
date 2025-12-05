<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

class Session
{
    public function __construct(
        private string $secureId,
        private string $title,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            secureId: (string) $data['secureId'],
            title: (string) $data['title'],
        );
    }

    public function getSecureId(): string { return $this->secureId; }
    public function getTitle(): string { return $this->title; }
}
