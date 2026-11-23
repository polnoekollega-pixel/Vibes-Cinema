<?php
/**
 * Vibes Cinema — AJAX-обработчик переключения избранного
 * Возвращает JSON: { ok, active } или { login: true } если нужно авторизоваться
 */
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if (!current_user()) {
    echo json_encode(['login' => true]);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Неверный id']);
    exit;
}

$pdo = db();
$uid = current_user()['id'];

// Проверка: существует ли контент
$st = $pdo->prepare('SELECT id FROM contents WHERE id = ?');
$st->execute([$id]);
if (!$st->fetch()) {
    echo json_encode(['ok' => false, 'error' => 'Контент не найден']);
    exit;
}

// Проверка текущего состояния
$st = $pdo->prepare('SELECT 1 FROM favorites WHERE user_id = ? AND content_id = ?');
$st->execute([$uid, $id]);
$exists = (bool)$st->fetch();

if ($exists) {
    $pdo->prepare('DELETE FROM favorites WHERE user_id = ? AND content_id = ?')->execute([$uid, $id]);
    echo json_encode(['ok' => true, 'active' => false]);
} else {
    $pdo->prepare('INSERT INTO favorites (user_id, content_id) VALUES (?, ?)')->execute([$uid, $id]);
    echo json_encode(['ok' => true, 'active' => true]);
}