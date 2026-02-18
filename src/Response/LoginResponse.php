<?php

declare(strict_types=1);

namespace SyrtisClient\Response;

use SyrtisClient\Entity\User;
use SyrtisClientInternal\Entity\UserConfig;
use SyrtisClientInternal\Repository\UserConfigRepository;
use Wexample\PhpApi\Common\AbstractApiEntitiesClient;
use SyrtisClient\Repository\UserRepository;

class LoginResponse
{
    public function __construct(
        private array $response,
        private AbstractApiEntitiesClient $client
    ) {
    }

    public function getUser(): User
    {
        $userData = $this->response['data']['user'] ?? null;
        if (! is_array($userData)) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        $entityData = $userData['entity'] ?? null;
        if (! is_array($entityData)) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        $repository = $this->client->getRepository(User::class);
        if (! $repository instanceof UserRepository) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        $user = $repository->hydrateFromApiItem($userData);
        if (! $user instanceof User) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        return $user;
    }

    public function getUserData(): array
    {
        $userData = $this->getUserApiData();
        $entityData = $userData['entity'] ?? null;

        if (! is_array($entityData)) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        return $entityData;
    }

    public function getUserApiData(): array
    {
        $userData = $this->response['data']['user'] ?? null;
        if (! is_array($userData)) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        return $userData;
    }

    public function getToken(): string
    {
        $user = $this->getUser();
        $token = $user->retrieveMetadata('token');

        if (! is_string($token) || $token === '') {
            throw new \RuntimeException('ERR_MISSING_TOKEN');
        }

        return $token;
    }

    /**
     * @return UserConfig[]
     */
    public function getUserConfig(): array
    {
        $user = $this->getUser();
        $config = $user->retrieveMetadata('userConfig');
        if (! is_array($config)) {
            return [];
        }

        $repository = $this->client->getRepository(UserConfig::class);
        if (! $repository instanceof UserConfigRepository) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        $entities = [];
        foreach ($config as $item) {
            if (! is_array($item)) {
                continue;
            }
            $entities[] = $repository->hydrateFromApiItem($item);
        }

        return $entities;
    }
}
