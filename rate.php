<?php
/**
 * Vibes Cinema — AJAX-обработчик оценки контента
 * Возвращает JSON: { ok } или { login: true }
 */
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if (!current_user()) {
    echo json_encode(['login' => true]);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);

if ($id <= 0 || $rating < 1 || $rating > 5) {
    echo json_encode(['ok' => false, 'error' => 'Некорректные данные']);
    exit;
}

$pdo = db();
$uid = current_user()['id'];

// Проверка существования контента
$st = $pdo->prepare('SELECT id FROM contents WHERE id = ?');
$st->execute([$id]);
if (!$st->fetch()) {
    echo json_encode(['ok' => false, 'error' => 'Контент не найден']);
    exit;
}

// Сохранение или обновление оценки
$pdo->prepare(
    'INSERT INTO ratings (user_id, content_id, rating) VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE rating = VALUES(rating)'
)->execute([$uid, $id, $rating]);

echo json_encode(['ok' => true, 'rating' => $rating]);