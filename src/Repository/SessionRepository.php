<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Session;
use Wexample\PhpApi\Common\AbstractApiRepository;
use Wexample\PhpApi\Const\HttpMethod;

class SessionRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Session::class;
    }

    public function fetch(string $secureId): Session
    {
        $data = $this->client->requestJson(
            HttpMethod::GET,
            '/api/' . $this->buildPath('show/' . rawurlencode($secureId))
        );

        $payload = is_array($data['data'] ?? null) ? $data['data'] : $data;

        return $this->createFromApiItem($payload);
    }
}
