<?php
// src/core/Validator.php

class Validator {

    /**
     * Перевіряє, чи є рядок валідним JSON.
     * Корисно для налаштувань або імпорту даних.
     * * @param string $string
     * @return bool|string Повертає true, якщо все ок, або текст помилки.
     */
    public static function checkJSON($string) {
        if (empty($string)) return true; // Пустий рядок вважаємо не помилкою (залежить від логіки)

        json_decode($string);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return "Помилка JSON: " . json_last_error_msg();
        }

        return true;
    }

    /**
     * Перевіряє URL адресу.
     * Також перевіряє, чи використовується безпечний протокол HTTPS.
     * * @param string $url
     * @return bool|string
     */
    public static function checkURL($url) {
        // Видаляємо зайві пробіли
        $url = trim($url);

        // Стандартна перевірка PHP
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "Некоректний формат посилання.";
        }

        // Додаткова перевірка на безпеку (для держустанов важливо HTTPS)
        $parsed = parse_url($url);
        if (isset($parsed['scheme']) && $parsed['scheme'] !== 'https') {
            return "Увага: Посилання використовує незахищений протокол HTTP. Рекомендовано HTTPS.";
        }

        return true;
    }

    /**
     * Очистка тексту від потенційно небезпечних тегів (XSS захист)
     * перед збереженням у базу.
     */
    public static function sanitizeText($text) {
        return htmlspecialchars(strip_tags($text));
    }
}