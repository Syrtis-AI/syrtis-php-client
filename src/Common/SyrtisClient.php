<?php

declare(strict_types=1);

namespace SyrtisClient\Common;

use GuzzleHttp\ClientInterface;
use SyrtisClient\Entity\Project;
use SyrtisClient\Entity\Session;
use SyrtisClient\Repository\SessionRepository;
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
    public const string DEFAULT_BASE_URL = 'https://api.syrtis.ai';

    public function __construct(
        string $baseUrl,
        ?string $apiKey = null,
        ?ClientInterface $httpClient = null,
        array $defaultHeaders = [],
    )
    {
        parent::__construct(
            $baseUrl ?: self::DEFAULT_BASE_URL,
            $apiKey,
            $httpClient,
            $defaultHeaders
        );

        $this->setDefaultHeader(
            'Content-Type',
            'application/json'
        );
    }

    protected function getRepositoryClasses(): array
    {
        return [
            SessionRepository::class,
        ];
    }

    public function getProjectList(): array
    {
        // TODO Should be made generic.
        $data = $this->requestJson(HttpMethod::GET, "/api/project/list");

        $payload = is_array($data['data'] ?? null) ? $data['data'] : $data;

        return Project::fromArrayCollection($payload['items']);
    }
}
