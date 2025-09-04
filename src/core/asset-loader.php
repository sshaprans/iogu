<?php
// src/core/asset-loader.php

/**
 * Отримує правильний шлях до файлу (CSS/JS) зі збірки Webpack.
 * @param string $file Оригінальне ім'я файлу (напр., 'main.css' або 'main.js').
 * @return string Повертає шлях від кореня сайту (напр., '/assets/css/main.abcdef123.css').
 */
function asset(string $file): string
{
    // Використовуємо static, щоб маніфест завантажувався лише один раз за запит
    static $manifest = null;

    if ($manifest === null) {
        // === ФІНАЛЬНЕ ВИПРАВЛЕННЯ ДЛЯ WINDOWS ===
        // 1. Отримуємо шлях до поточної папки (напр., G:\iogu\src\core)
        $currentDir = __DIR__;
        // 2. Замінюємо всі зворотні слеші (\) на прямі (/), щоб шлях був універсальним.
        $normalizedDir = str_replace('\\', '/', $currentDir);
        // 3. Будуємо надійний відносний шлях до маніфесту.
        $manifestPath = $normalizedDir . '/../../dist/assets.json';

        if (file_exists($manifestPath)) {
            $manifestData = file_get_contents($manifestPath);
            $manifest = json_decode($manifestData, true);

            // Захист від пошкодженого або порожнього JSON файлу.
            if (!is_array($manifest)) {
                $manifest = [];
            }
        } else {
            // Якщо файл маніфесту взагалі не існує
            $manifest = [];
        }
    }

    $path = $manifest[$file] ?? '';

    // Завжди повертаємо шлях від кореня сайту, починаючи зі слеша
    return '/' . ltrim($path, '/');
}

