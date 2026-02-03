<?php

declare(strict_types=1);

namespace SyrtisClient\Entity;

use Wexample\Helpers\Class\Traits\HasSnakeShortClassNameClassTrait;

abstract class AbstractApiEntity extends \Wexample\PhpApi\Common\AbstractApiEntity
{
    use HasSnakeShortClassNameClassTrait;

    public static function getEntityName(): string
    {
        return static::getSnakeShortClassName();
    }

    public function isStub(): bool
    {
        return false;
    }

    public function __call(
        string $name,
        array $arguments
    ): mixed {
        if (preg_match('/^get(.+)$/', $name, $matches) === 1) {
            $relationship = $this->findRelationshipByName($matches[1]);
            if ($relationship !== null) {
                return $relationship;
            }

            $relationships = $this->findRelationshipsByName($matches[1]);
            if ($relationships !== []) {
                return $relationships;
            }
        }

        return parent::__call($name, $arguments);
    }

    protected function findRelationshipByName(string $name): ?\Wexample\PhpApi\Common\AbstractApiEntity
    {
        $normalizedTarget = $this->normalizeRelationshipName($name);

        foreach ($this->relationships as $relationship) {
            if (! $relationship instanceof \Wexample\PhpApi\Common\AbstractApiEntity) {
                continue;
            }

            if ($relationship instanceof ApiEntityStub) {
                if ($this->normalizeRelationshipName($relationship->getTargetName()) === $normalizedTarget) {
                    return $relationship;
                }
            }

            $shortName = (new \ReflectionClass($relationship))->getShortName();

            if (strcasecmp($shortName, $name) === 0) {
                return $relationship;
            }

            $entityName = $relationship::getEntityName();

            if ($this->normalizeRelationshipName($entityName) === $normalizedTarget) {
                return $relationship;
            }
        }

        return null;
    }

    /**
     * @return \Wexample\PhpApi\Common\AbstractApiEntity[]
     */
    protected function findRelationshipsByName(string $name): array
    {
        $normalizedTarget = $this->normalizeRelationshipName($name);
        $matches = [];

        foreach ($this->relationships as $relationship) {
            if (! $relationship instanceof \Wexample\PhpApi\Common\AbstractApiEntity) {
                continue;
            }

            if ($relationship instanceof ApiEntityStub) {
                if ($this->normalizeRelationshipName($relationship->getTargetName()) === $normalizedTarget) {
                    $matches[] = $relationship;
                }
                continue;
            }

            $shortName = (new \ReflectionClass($relationship))->getShortName();
            if (strcasecmp($shortName, $name) === 0) {
                $matches[] = $relationship;
                continue;
            }

            $entityName = $relationship::getEntityName();
            if ($this->normalizeRelationshipName($entityName) === $normalizedTarget) {
                $matches[] = $relationship;
            }
        }

        return $matches;
    }
}
