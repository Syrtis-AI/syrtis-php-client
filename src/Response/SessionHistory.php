<?php

declare(strict_types=1);

namespace SyrtisClient\Response;

use SyrtisClient\Entity\Message;

final class SessionHistory
{
    /**
     * @param Message[] $messages
     * @param bool|null $hasMore Null when the API did not compute it (no length requested).
     */
    public function __construct(
        private readonly array $messages,
        private readonly ?bool $hasMore,
    ) {
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getHasMore(): ?bool
    {
        return $this->hasMore;
    }
}
