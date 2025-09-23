<?php
// src/core/asset-loader.php

/**
 * Отримує правильний шлях до файлу (CSS/JS) зі збірки Webpack.
 * @param string $file Оригінальне ім'я файлу (напр., 'main.css' або 'main.js').
 * @return string Повертає шлях від кореня сайту (напр., '/assets/css/main.abcdef123.css').
 */
function asset(string $file): string
{
    static $manifest = null;

    if ($manifest === null) {
        $manifestPath = $_SERVER['DOCUMENT_ROOT'] . '/assets.json';

        if (file_exists($manifestPath)) {
            $manifestData = file_get_contents($manifestPath);
            $manifest = json_decode($manifestData, true);

            if (!is_array($manifest)) {
                $manifest = [];
            }
        } else {
            $manifest = [];
        }
    }

    $path = $manifest[$file] ?? '';

    return '/' . ltrim($path, '/');
}

