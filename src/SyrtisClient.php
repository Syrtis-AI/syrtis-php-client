<?php

declare(strict_types=1);

namespace SyrtisClient;

use function array_merge;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

use function ltrim;

use Psr\Http\Message\ResponseInterface;

use function rtrim;

use SyrtisClient\Exceptions\ApiException;

/**
 * Minimal Syrtis API client built on top of Guzzle.
 *
 * @example
 * $client = new Client('https://api.syrtis.ai', 'api-key-here');
 * $response = $client->get('/v1/things', ['query' => ['page' => 1]]);
 */
final class SyrtisClient extends Client
{

}
