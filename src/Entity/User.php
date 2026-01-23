<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

use DateTimeImmutable;

class User extends AbstractApiEntity
{
    public static function getEntityName(): string
    {
        return 'user';
    }

    public function __construct(
        string $secureId,
        protected ?string $username,
        protected ?DateTimeImmutable $dateCreated,
        protected ?DateTimeImmutable $dateLastLogin,
        protected ?string $email,
        protected ?string $firstName,
        protected ?string $lastName,
    ) {
        parent::__construct(
            secureId: $secureId
        );
    }

    public static function fromArray(array $data): static
    {
        return new self(
            secureId: (string) ($data['secureId'] ?? ''),
            username: isset($data['username']) ? (string) $data['username'] : null,
            dateCreated: self::parseDate($data['date_created'] ?? null),
            dateLastLogin: self::parseDate($data['date_last_login'] ?? null),
            email: isset($data['email']) ? (string) $data['email'] : null,
            firstName: isset($data['first_name']) ? (string) $data['first_name'] : null,
            lastName: isset($data['last_name']) ? (string) $data['last_name'] : null,
        );
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getDateCreated(): ?DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function getDateLastLogin(): ?DateTimeImmutable
    {
        return $this->dateLastLogin;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    private static function parseDate(mixed $value): ?DateTimeImmutable
    {
        if (! $value) {
            return null;
        }

        try {
            return new DateTimeImmutable((string) $value);
        } catch (\Exception) {
            return null;
        }
    }
}
