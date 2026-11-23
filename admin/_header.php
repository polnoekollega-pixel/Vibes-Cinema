<?php
/**
 * Vibes Cinema — шапка админ-панели
 * Доступ только для администратора
 */
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$adminUser = current_user();
$adminTitle = $adminTitle ?? 'Панель администратора';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminTitle) ?> — Vibes Cinema</title>
    <link rel="icon" type="image/svg+xml" href="../assets/img/favicon.svg">
    <meta name="theme-color" content="#0b0d17">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .admin-side { width: 250px; background: #0a0c15; border-right: 1px solid var(--border); padding: 24px 16px; color: #fff; flex-shrink: 0; }
        .admin-side .logo { color:#fff; }
        .admin-nav { display: flex; flex-direction: column; gap: 4px; margin-top: 24px; }
        .admin-nav a { padding: 10px 14px; border-radius: 10px; color: #cfd3e0; text-decoration: none; white-space: nowrap; }
        .admin-nav a:hover, .admin-nav a.active { background: rgba(255,255,255,.08); color: #fff; }
        .admin-main { flex: 1; min-width: 0; padding: 28px; background: var(--bg); }
        .admin-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .admin-top h1 { margin: 0; font-size: 24px; }
        .admin-form { background: var(--card); padding: 20px; border-radius: 14px; border: 1px solid var(--border); margin-bottom: 28px; }

        @media (max-width: 1024px) {
            .admin-layout { flex-direction: column; }
            .admin-side { width: 100%; padding: 16px; border-right: none; border-bottom: 1px solid var(--border); }
            .admin-nav { flex-direction: row; flex-wrap: wrap; gap: 6px; margin-top: 14px; }
            .admin-nav hr { display: none; }
            .admin-main { padding: 20px 16px; }
        }
        @media (max-width: 600px) {
            .admin-main { padding: 16px 12px; }
            .admin-top h1 { font-size: 20px; }
            .admin-form { padding: 14px; }
            .admin-table { display: block; width: 100%; overflow-x: auto; white-space: nowrap; }
            .admin-table th, .admin-table td { padding: 10px; }
            .stat-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="admin-layout">
    <aside class="admin-side">
        <a href="../index.php" class="logo">✨ <span>Vibes Cinema</span></a>
        <nav class="admin-nav">
            <a href="index.php">📊 Дашборд</a>
            <a href="contents.php" class="<?= basename($_SERVER['PHP_SELF']) === 'contents.php' ? 'active' : '' ?>">🎬 Контент</a>
            <a href="categories.php" class="<?= basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : '' ?>">🏷️ Категории</a>
            <a href="orders.php" class="<?= basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : '' ?>">📦 Заказы</a>
            <a href="plans.php" class="<?= basename($_SERVER['PHP_SELF']) === 'plans.php' ? 'active' : '' ?>">💳 Тарифы</a>
            <hr style="border-color:rgba(255,255,255,.1); margin: 16px 0;">
            <a href="../index.php">🌐 На сайт</a>
            <a href="../logout.php">🚪 Выйти</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-top">
            <h1><?= e($adminTitle) ?></h1>
            <span class="badge-type"><?= e($adminUser['first_name']) ?> <?= e($adminUser['last_name']) ?> (админ)</span>
        </div>
        <?php show_flash(); ?>