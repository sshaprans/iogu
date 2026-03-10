<?php
// src/core/Validator.php

class Validator {
    /**
     * @return bool|string
     */
    public static function checkJSON(?string $string) {
        if (empty($string)) {
            return true;
        }

        json_decode($string);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return "Помилка JSON: " . json_last_error_msg();
        }

        return true;
    }

    /**
     * @return bool|string
     */
    public static function checkURL(?string $url) {
        if (empty($url)) {
            return "URL не може бути порожнім.";
        }

        $url = trim($url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "Некоректний формат посилання.";
        }

        $parsed = parse_url($url);
        if (isset($parsed['scheme']) && strtolower($parsed['scheme']) !== 'https') {
            return "Увага: Посилання використовує незахищений протокол HTTP. Рекомендовано HTTPS.";
        }

        return true;
    }

    /**
     * Очистка від небезпечних тегів (XSS захист).
     */
    public static function sanitizeText(?string $text): string {
        if ($text === null) {
            return '';
        }
        return htmlspecialchars(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}