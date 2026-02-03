<?php

declare(strict_types=1);

namespace SyrtisClient\Common;

use Symfony\Component\Yaml\Yaml;

class EntitySchemaLoader
{
    /**
     * @param string[] $directories
     * @return array<string, array>
     */
    public function load(array $directories): array
    {
        $schemas = [];

        foreach ($directories as $directory) {
            $directory = rtrim($directory, '/');
            if (! is_dir($directory)) {
                continue;
            }

            foreach (glob($directory . '/*.yml') ?: [] as $file) {
                if (! is_file($file)) {
                    continue;
                }

                $schema = Yaml::parseFile($file);
                if (! is_array($schema)) {
                    continue;
                }

                $name = $schema['name'] ?? null;
                if (! is_string($name) || $name === '') {
                    continue;
                }

                $schemas[$name] = $this->mergeSchema($schemas[$name] ?? [], $schema);
            }
        }

        return $schemas;
    }

    private function mergeSchema(array $base, array $override): array
    {
        $baseProps = $this->normalizeProperties($base['properties'] ?? []);
        $overrideProps = $this->normalizeProperties($override['properties'] ?? []);

        $merged = array_replace($base, $override);
        $merged['properties'] = array_values(array_merge($baseProps, $overrideProps));

        return $merged;
    }

    /**
     * @param array<int, array> $properties
     * @return array<string, array>
     */
    private function normalizeProperties(array $properties): array
    {
        $normalized = [];

        foreach ($properties as $property) {
            if (! is_array($property)) {
                continue;
            }

            $name = $property['name'] ?? null;
            if (! is_string($name) || $name === '') {
                continue;
            }

            $normalized[$name] = $property;
        }

        return $normalized;
    }
}
