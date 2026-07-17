<?php

declare(strict_types=1);

namespace SyrtisClient\Response;

/**
 * Response of GET session/subscribe-info/{secureId}: a scoped, short-lived
 * Mercure subscriber JWT and its hub location.
 */
final class SessionSubscribeInfo
{
    /**
     * @param string[] $topics
     */
    public function __construct(
        private readonly string $hubUrl,
        private readonly string $jwt,
        private readonly array $topics,
        private readonly string $expiresAt,
    ) {
    }

    public function getHubUrl(): string
    {
        return $this->hubUrl;
    }

    public function getJwt(): string
    {
        return $this->jwt;
    }

    /**
     * @return string[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }
}
