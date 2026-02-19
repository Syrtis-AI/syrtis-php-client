#!/usr/bin/env php
<?php

declare(strict_types=1);

$rootDir = dirname(__DIR__);
$dataDir = $rootDir . '/data/entity';
$repositoryDir = $rootDir . '/src/Repository';
$templatePath = $rootDir . '/bin/template/Repository.php.tpl';

if (! is_dir($dataDir)) {
    fwrite(STDERR, "Error: missing data directory: {$dataDir}\n");
    exit(1);
}

if (! is_dir($repositoryDir) && ! mkdir($repositoryDir, 0775, true) && ! is_dir($repositoryDir)) {
    fwrite(STDERR, "Error: cannot create repository directory: {$repositoryDir}\n");
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

    $targetPath = $repositoryDir . '/' . $className . 'Repository.php';

    if (is_file($targetPath)) {
        $skipped++;
        continue;
    }

    $content = buildRepositoryClass($template, $className);
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

function buildRepositoryClass(string $template, string $className): string
{
    return str_replace('{{CLASS_NAME}}', $className, $template);
}
