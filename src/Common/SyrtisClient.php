<?php

declare(strict_types=1);

namespace SyrtisClient\Common;

use GuzzleHttp\ClientInterface;
use Wexample\PhpApiEntity\Common\AbstractApiEntitiesClient;

/**
 * Syrtis API client built on top of Guzzle.
 *
 * @example
 * $client = new SyrtisClient(host: 'https://api.syrtis.ai', apiKey: 'api-key-here');
 * $sessions = $client->getRepository(Session::class);
 */
class SyrtisClient extends AbstractApiEntitiesClient
{
    public const string API_VERSION_2025_3 = '2025-3';
    public const string API_VERSION_2026_1 = '2026-1';
    public const string API_VERSION_DEFAULT = self::API_VERSION_2026_1;

    private readonly string $apiVersion;

    public function __construct(
        string $host,
        ?string $apiVersion = null,
        ?string $apiKey = null,
        ?ClientInterface $httpClient = null,
        array $defaultHeaders = [],
        bool $debugEnabled = false,
    ) {
        $version = $apiVersion ?? self::API_VERSION_DEFAULT;
        $normalizedHost = rtrim($host, '/') . '/';

        parent::__construct(
            $normalizedHost . 'api/' . $version . '/',
            $apiKey,
            $httpClient,
            $defaultHeaders
        );

        $this->apiVersion = $version;

        $this->setDebugEnabled($debugEnabled);

        $this->setDefaultHeader(
            'Content-Type',
            'application/json'
        );
    }

    /**
     * Version segment of every API path — also prefixes the versioned
     * Mercure topics ({apiVersion}/entity/session/event/{id}).
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    protected function getRepositoryClasses(): array
    {
        return $this->discoverRepositoryClasses(
            dirname(__DIR__),
            'SyrtisClient\\Repository'
        );
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
}
