#!/usr/bin/env php
<?php

declare(strict_types=1);

// IMPORTANT: keep this script in sync with php-client-internal/bin/generate-entities.php.
// Any improvement here must be mirrored there.

$rootDir = dirname(__DIR__);
$dataDir = $rootDir . '/data/entity';
$entityDir = $rootDir . '/src/Entity';
$templatePath = $rootDir . '/bin/template/Entity.php.tpl';
$rootNamespace = detectRootNamespace($rootDir);
$entityNamespace = $rootNamespace . '\\Entity';
$abstractEntityClass = detectAbstractEntityClass($rootDir, $entityNamespace);

if (! is_dir($dataDir)) {
    fwrite(STDERR, "Error: missing data directory: {$dataDir}\n");
    exit(1);
}

if (! is_dir($entityDir) && ! mkdir($entityDir, 0775, true) && ! is_dir($entityDir)) {
    fwrite(STDERR, "Error: cannot create entity directory: {$entityDir}\n");
    exit(1);
}

$template = file_get_contents($templatePath);
if ($template === false || $template === '') {
    fwrite(STDERR, "Error: missing or empty template: {$templatePath}\n");
    exit(1);
}

$files = glob($dataDir . '/*.yml');
if ($files === false) {
    fwrite(STDERR, "Error: cannot list YAML files in {$dataDir}\n");
    exit(1);
}

sort($files);

$created = 0;
$skipped = 0;

foreach ($files as $filePath) {
    $baseName = pathinfo($filePath, PATHINFO_FILENAME);
    $className = toStudlyCase($baseName);

    if ($className === '' || $className === 'AbstractApiEntity') {
        $skipped++;
        continue;
    }

    $targetPath = $entityDir . '/' . $className . '.php';

    if (is_file($targetPath)) {
        $skipped++;
        continue;
    }

    $content = buildEntityClass(
        $template,
        $className,
        $entityNamespace,
        $abstractEntityClass
    );
    file_put_contents($targetPath, $content);
    $created++;
    echo "Created {$targetPath}\n";
}

echo "Done: created={$created}, skipped={$skipped}\n";

function toStudlyCase(string $value): string
{
    $value = strtolower(trim($value));
    if ($value === '') {
        return '';
    }

    $value = preg_replace('/[^a-z0-9]+/', ' ', $value);
    if (! is_string($value)) {
        return '';
    }

    return str_replace(' ', '', ucwords($value));
}

function buildEntityClass(
    string $template,
    string $className,
    string $entityNamespace,
    string $abstractEntityClass
): string
{
    return str_replace(
        ['{{CLASS_NAME}}', '{{ENTITY_NAMESPACE}}', '{{ABSTRACT_ENTITY_CLASS}}'],
        [$className, $entityNamespace, '\\' . ltrim($abstractEntityClass, '\\')],
        $template
    );
}

function detectRootNamespace(string $rootDir): string
{
    $composerPath = $rootDir . '/composer.json';
    $composer = json_decode((string) file_get_contents($composerPath), true);
    $autoload = $composer['autoload']['psr-4'] ?? [];
    if (! is_array($autoload) || empty($autoload)) {
        fwrite(STDERR, "Error: unable to read PSR-4 autoload namespace from {$composerPath}\n");
        exit(1);
    }

    $firstNamespace = (string) array_key_first($autoload);
    return rtrim($firstNamespace, '\\');
}

function detectAbstractEntityClass(string $rootDir, string $entityNamespace): string
{
    if (is_file($rootDir . '/src/Entity/AbstractApiEntity.php')) {
        return $entityNamespace . '\\AbstractApiEntity';
    }

    return 'SyrtisClient\\Entity\\AbstractApiEntity';
}
