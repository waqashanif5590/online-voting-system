<?php
$projectRoot = realpath(dirname(__DIR__));
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

if ($projectRoot === false || $documentRoot === false) {
    throw new RuntimeException('Unable to determine the application or document root.');
}

$projectRoot = str_replace('\\', '/', $projectRoot);
$documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');

if (strcasecmp($projectRoot, $documentRoot) === 0) {
    $basePath = '';
} else {
    $documentRootPrefix = $documentRoot . '/';
    if (strncasecmp($projectRoot, $documentRootPrefix, strlen($documentRootPrefix)) !== 0) {
        throw new RuntimeException('The application directory must be inside the web document root.');
    }

    $basePath = '/' . trim(substr($projectRoot, strlen($documentRootPrefix)), '/');
}

if (!defined('APP_ROOT')) {
    define('APP_ROOT', $projectRoot);
}

if (!defined('APP_BASE_URL')) {
    define('APP_BASE_URL', $basePath);
}

function app_url(string $path = ''): string
{
    return APP_BASE_URL . '/' . ltrim($path, '/');
}
