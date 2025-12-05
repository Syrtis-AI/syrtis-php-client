<?php

declare(strict_types=1);

namespace SyrtisClient;

/**
 * Minimal Syrtis API client built on top of Guzzle.
 *
 * @example
 * $client = new Client('https://api.syrtis.ai', 'api-key-here');
 * $response = $client->get('/v1/things', ['query' => ['page' => 1]]);
 */
class SyrtisClient extends \Wexample\PhpApi\Client
{
    private string $baseUrl = 'https://api.syrtis.ai';
}
