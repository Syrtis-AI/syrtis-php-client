<?php

declare(strict_types=1);

namespace SyrtisClient\Common;

use GuzzleHttp\ClientInterface;
use SyrtisClient\Repository\ScenarioRepository;
use SyrtisClient\Repository\SessionRepository;
use SyrtisClient\Repository\UserRepository;
use SyrtisClient\Entity\User;
use SyrtisClient\Response\LoginResponse;
use Wexample\PhpApi\Common\AbstractApiEntitiesClient;
use Wexample\PhpApi\Const\HttpMethod;

/**
 * Minimal Syrtis API client built on top of Guzzle.
 *
 * @example
 * $client = new Client('https://api.syrtis.ai', 'api-key-here');
 * $response = $client->get('/v1/things', ['query' => ['page' => 1]]);
 */
class SyrtisClient extends AbstractApiEntitiesClient
{
    public const string DEFAULT_BASE_URL = 'https://api.syrtis.ai/api/';
    protected ?array $entitySchemas = null;

    public function __construct(
        string $baseUrl,
        ?string $apiKey = null,
        ?ClientInterface $httpClient = null,
        array $defaultHeaders = [],
        bool $debugEnabled = false,
    )
    {
        parent::__construct(
            $baseUrl ?: self::DEFAULT_BASE_URL,
            $apiKey,
            $httpClient,
            $defaultHeaders
        );

        $this->setDebugEnabled($debugEnabled);

        $this->setDefaultHeader(
            'Content-Type',
            'application/json'
        );
    }

    protected function getRepositoryClasses(): array
    {
        return [
            ScenarioRepository::class,
            SessionRepository::class,
            UserRepository::class,
        ];
    }

    /**
     * @return string[]
     */
    protected function getEntitySchemaDirectories(): array
    {
        return [
            dirname(__DIR__, 2) . '/data/entity',
        ];
    }

    /**
     * @return array<string, array>
     */
    public function getEntitySchemas(): array
    {
        if ($this->entitySchemas !== null) {
            return $this->entitySchemas;
        }

        $loader = new EntitySchemaLoader();
        $this->entitySchemas = $loader->load($this->getEntitySchemaDirectories());

        return $this->entitySchemas;
    }

    public function login(string $login, string $password): LoginResponse
    {
        $response = $this->requestJson(
            HttpMethod::POST,
            'auth/login',
            [
                'json' => [
                    'login' => $login,
                    'password' => $password,
                ],
            ]
        );

        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepository(User::class);

        return new LoginResponse($response, $this);
    }
}
