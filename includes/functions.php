<?php
/**
 * Vibes Cinema — вспомогательные функции сайта
 * Экранирование, работа с сессией, роль пользователя, форматирование
 */
require_once __DIR__ . '/config.php';

/**
 * Экранирование вывода для защиты от XSS
 */
function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

/**
 * Текущий авторизованный пользователь или null
 */
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Проверка авторизации: если пользователь не вошёл — редирект на страницу входа
 */
function require_login(): void
{
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Роль текущего пользователя ('admin' | 'subscriber' | 'user' | null)
 */
function current_role(): ?string
{
    $u = current_user();
    return $u['role'] ?? null;
}

/**
 * Проверка прав администратора
 */
function require_admin(): void
{
    require_login();
    if (current_role() !== 'admin') {
        header('Location: index.php');
        exit;
    }
}

/**
 * Форматирование суммы в рублях
 */
function money($sum): string
{
    return number_format((float)$sum, 0, ',', ' ') . ' ₽';
}

/**
 * Склонение числительных по-русски: 1 серия, 2 серии, 5 серий
 */
function plural_ru(int $n, string $one, string $few, string $many): string
{
    $mod100 = abs($n) % 100;
    $mod10 = $mod100 % 10;
    if ($mod100 > 10 && $mod100 < 20) return $many;
    if ($mod10 > 1 && $mod10 < 5) return $few;
    if ($mod10 === 1) return $one;
    return $many;
}

/**
 * Общее число серий сериала (кэш на время запроса)
 */
function content_episodes_total(int $contentId): int
{
    static $map = null;
    if ($map === null) {
        $map = [];
        $rows = db()->query(
            'SELECT s.content_id, COUNT(e.id) AS cnt
             FROM seasons s LEFT JOIN episodes e ON e.season_id = s.id
             GROUP BY s.content_id'
        )->fetchAll();
        foreach ($rows as $r) {
            $map[(int)$r['content_id']] = (int)$r['cnt'];
        }
    }
    return $map[$contentId] ?? 0;
}

/**
 * Генерация CSRF-токена для защиты форм
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Проверка CSRF-токена формы
 */
function csrf_check(): void
{
    if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        die('Ошибка проверки CSRF-токена. Обновите страницу и попробуйте снова.');
    }
}

/**
 * Добавление flash-сообщения
 */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

/**
 * Вывод всех flash-сообщений (один раз)
 */
function show_flash(): void
{
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $f) {
            $cls = $f['type'] === 'error' ? 'flash-error' : 'flash-success';
            echo '<div class="flash ' . $cls . '">' . e($f['message']) . '</div>';
        }
        unset($_SESSION['flash']);
    }
}

/**
 * Средняя оценка контента (фильма/сериала)
 */
function avg_rating(int $contentId): float
{
    $st = db()->prepare('SELECT AVG(rating) AS r FROM ratings WHERE content_id = ?');
    $st->execute([$contentId]);
    $r = (float)$st->fetch()['r'];
    return round($r, 1);
}

/**
 * Проверка — находится ли контент в избранном у пользователя
 */
function in_favorites(int $contentId): bool
{
    $u = current_user();
    if (!$u) return false;
    $st = db()->prepare('SELECT 1 FROM favorites WHERE user_id = ? AND content_id = ?');
    $st->execute([$u['id'], $contentId]);
    return (bool)$st->fetch();
}

/**
 * Размер шрифта рейтинга для звёзд (dict-подобная функция не нужна)
 */
function rating_stars(float $rating): string
{
    $full = (int)floor($rating);
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        $out .= $i <= $full
            ? '<span class="star filled">★</span>'
            : '<span class="star">★</span>';
    }
    return $out;
}