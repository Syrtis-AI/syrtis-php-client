<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Entity\Project;
use Wexample\PhpApi\Common\AbstractApiRepository;
use Wexample\PhpApi\Const\HttpMethod;

class ProjectRepository extends AbstractApiRepository
{
    public static function getEntityType(): string
    {
        return Project::class;
    }

    /**
     * @return Project[]
     */
    public function fetchList(): array
    {
        $data = $this->client->requestJson(
            HttpMethod::GET,
            '/api/' . $this->buildPath('list')
        );

        $payload = is_array($data['data'] ?? null) ? $data['data'] : $data;
        $items = is_array($payload['items'] ?? null) ? $payload['items'] : [];

        return $this->createFromApiCollection($items);
    }
}
