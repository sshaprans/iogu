<?php
/**
 * src/core/asset-loader.php
 */

function asset(string $file): ?string
{
    static $manifest = null;

    if ($manifest === null) {
        $manifestPath = __DIR__ . '/../assets.json';

        if (file_exists($manifestPath)) {
            $manifestData = file_get_contents($manifestPath);
            $manifest = json_decode($manifestData, true);
        }

        if (!is_array($manifest)) {
            $manifest = [];
        }
    }

    if (!isset($manifest[$file])) {
        return null;
    }

    return '/' . ltrim($manifest[$file], '/');
}