<?php
/**
 * Illuminos — онлайн-кинотеатр
 * Файл конфигурации: параметры подключения к базе данных и общие настройки сайта
 */

// Параметры подключения к СУБД MySQL (Denwer/OpenServer/XAMPP)
define('DB_HOST', 'localhost');     // хост базы данных
define('DB_PORT', 3306);            // порт MySQL в OSPanel по умолчанию (MariaDB-10.8-Win10)
define('DB_NAME', 'illuminos');     // имя базы данных
define('DB_USER', 'root');          // пользователь MySQL
define('DB_PASS', '');              // пароль MySQL (по умолчанию пустой)

// Настройки сессии и защиты
ini_set('session.cookie_httponly', 1);       // защита cookie от JavaScript
session_start();                              // старт сессии для авторизации

// Временная зона сайта
date_default_timezone_set('Asia/Yekaterinburg');

/**
 * Функция получения соединения с базой данных (PDO)
 * @return PDO
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die('Ошибка подключения к базе данных: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}