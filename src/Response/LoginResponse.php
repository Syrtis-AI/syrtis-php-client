<?php

declare(strict_types=1);

namespace SyrtisClient\Response;

use SyrtisClient\Entity\User;
use SyrtisClient\Repository\UserRepository;

class LoginResponse
{
    public function __construct(
        private array $response,
        private UserRepository $userRepository
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

        $user = $this->userRepository->hydrateFromApiItem($userData);
        if (! $user instanceof User) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        return $user;
    }

    public function getUserData(): array
    {
        $userData = $this->response['data']['user'] ?? null;
        if (! is_array($userData)) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        $entityData = $userData['entity'] ?? null;
        if (! is_array($entityData)) {
            throw new \RuntimeException('ERR_BAD_RESPONSE_FORMAT');
        }

        return $entityData;
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

    public function getUserConfig(): array
    {
        $user = $this->getUser();
        $config = $user->retrieveMetadata('userConfig');

        return is_array($config) ? $config : [];
    }
}
