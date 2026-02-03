<?php

declare(strict_types=1);

namespace SyrtisClient\Repository;

use SyrtisClient\Common\SyrtisClient;
use SyrtisClient\Entity\ApiEntityStub;
use Wexample\PhpApi\Common\AbstractApiEntity;
use Wexample\PhpApi\Common\AbstractApiRepository;

abstract class AbstractSyrtisRepository extends AbstractApiRepository
{
    /**
     * @return AbstractApiEntity
     */
    protected function createFromApiItem(
        array $data,
        array $metadata = [],
        array $relationships = [],
    ): AbstractApiEntity {
        return $this->hydrateFromApiItem($data, $metadata, $relationships);
    }

    /**
     * @return AbstractApiEntity
     */
    public function hydrateFromApiItem(
        array $data,
        array $metadata = [],
        array $relationships = [],
    ): AbstractApiEntity {
        $entityType = static::getEntityType();

        /** @var AbstractApiEntity $entity */
        $entity = $entityType::fromArray($data);
        $entity->setMetadata($metadata);
        $entity->setRelationships($this->buildRelationshipsForEntity($entityType, $data, $relationships));

        return $entity;
    }

    /**
     * @return AbstractApiEntity[]
     */
    protected function buildRelationshipsForEntity(
        string $entityType,
        array $data,
        array $relationships,
    ): array {
        $schemas = $this->getClient()->getEntitySchemas();
        $entityName = $entityType::getEntityName();
        $schema = $schemas[$entityName] ?? null;

        if (! is_array($schema)) {
            return [];
        }

        $output = [];

        foreach ($schema['properties'] ?? [] as $property) {
            if (! is_array($property)) {
                continue;
            }

            $type = strtolower((string) ($property['type'] ?? ''));
            if (! in_array($type, ['relation', 'collection'], true)) {
                continue;
            }

            $target = $property['target'] ?? null;
            if (! is_string($target) || $target === '') {
                continue;
            }

            $apiField = $property['apiField'] ?? $property['name'] ?? null;
            if (! is_string($apiField) || $apiField === '') {
                continue;
            }

            $value = $data[$apiField] ?? null;

            if ($type === 'relation') {
                $related = $this->resolveRelationshipEntity($target, $value, $relationships);
                if ($related !== null) {
                    $output[] = $related;
                }
                continue;
            }

            $items = is_array($value) ? $value : ($value === null ? [] : [$value]);
            foreach ($items as $item) {
                $related = $this->resolveRelationshipEntity($target, $item, $relationships);
                if ($related !== null) {
                    $output[] = $related;
                }
            }
        }

        return $output;
    }

    protected function resolveRelationshipEntity(
        string $target,
        mixed $value,
        array $relationships,
    ): ?AbstractApiEntity {
        if (is_array($value)) {
            [$item, $metadata, $itemRelationships] = $this->splitApiItem($value);

            $targetRepository = $this->getClient()->getRepository($target);
            if ($targetRepository instanceof self) {
                return $targetRepository->hydrateFromApiItem($item, $metadata, $itemRelationships);
            }

            $entityType = $targetRepository::getEntityType();
            return $entityType::fromArray($item);
        }

        if (is_string($value) && $value !== '' && isset($relationships[$value]) && is_array($relationships[$value])) {
            [$item, $metadata, $itemRelationships] = $this->splitApiItem($relationships[$value]);

            $targetRepository = $this->getClient()->getRepository($target);
            if ($targetRepository instanceof self) {
                return $targetRepository->hydrateFromApiItem($item, $metadata, $itemRelationships);
            }

            $entityType = $targetRepository::getEntityType();
            return $entityType::fromArray($item);
        }

        $id = is_string($value) ? $value : null;
        if ($id === null || $id === '') {
            return null;
        }

        return new ApiEntityStub($target, $id);
    }

    protected function getClient(): SyrtisClient
    {
        if (! $this->client instanceof SyrtisClient) {
            throw new \RuntimeException('SyrtisClient is required to hydrate relationships.');
        }

        return $this->client;
    }
}
