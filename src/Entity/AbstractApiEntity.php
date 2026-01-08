<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

abstract class AbstractApiEntity
{
    public function __construct(
        protected string $secureId,
    )
    {
    }

    abstract public static function fromArray(array $data): self;

    public static function fromArrayCollection(array $collection): array
    {
        $output = [];

        foreach ($collection as $data) {
            $output[] = static::fromArray($data);
        }

        return $output;
    }

    public function getSecureId(): string
    {
        return $this->secureId;
    }
}
