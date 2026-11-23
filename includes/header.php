<?php
/**
 * Vibes Cinema — общая шапка сайта (header)
 * Подключается на всех страницах, кроме страниц авторизации
 * @var string $pageTitle  заголовок страницы (задаётся до подключения)
 */
require_once __DIR__ . '/functions.php';
$user = current_user();
$pageTitle = $pageTitle ?? 'Vibes Cinema — онлайн-кинотеатр';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="Vibes Cinema — онлайн-кинотеатр: тысячи фильмов и сериалов в отличном качестве.">
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
    <meta name="theme-color" content="#0b0d17">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body data-nav-type="<?= $navType ?? '' ?>">
<header class="site-header">
    <div class="container header-inner">
        <a href="index.php" class="logo">
            <span class="logo-mark">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"></rect><path d="M10 8.7v6.6l5.5-3.3L10 8.7Z" fill="currentColor" stroke="none"></path></svg>
            </span>
            <span>Vibes Cinema</span>
        </a>

        <!-- Меню сайта -->
        <nav class="main-nav" id="mainNav">
            <a href="index.php" class="nav-link" data-nav="home">Главная</a>
            <a href="catalog.php?type=movie" class="nav-link" data-nav="movies">Фильмы</a>
            <a href="catalog.php?type=series" class="nav-link" data-nav="series">Сериалы</a>
            <a href="plans.php" class="nav-link" data-nav="plans">Тарифы</a>

            <!-- Поиск для мобильных: виден только в выпадающем меню -->
            <form class="nav-search" action="search.php" method="get">
                <input type="search" name="q" placeholder="Поиск фильмов и сериалов…" value="<?= e($_GET['q'] ?? '') ?>">
                <button type="submit" aria-label="Найти">🔍</button>
            </form>
        </nav>

        <!-- Поиск по фильмам и сериалам -->
        <form class="header-search" action="search.php" method="get">
            <input type="search" name="q" placeholder="Поиск фильмов и сериалов…" value="<?= e($_GET['q'] ?? '') ?>">
            <button type="submit" aria-label="Найти">🔍</button>
        </form>

        <div class="header-user">
            <?php if ($user): ?>
                <div class="user-menu">
                    <button class="user-btn" id="userBtn">
                        <span class="avatar"><?= mb_strtoupper(mb_substr($user['first_name'] ?: $user['login'], 0, 1)) ?></span>
                        <span class="user-name"><?= e($user['first_name'] ?: $user['login']) ?></span>
                        <span class="caret">▾</span>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="admin/index.php">⚙️ Админ-панель</a>
                        <?php endif; ?>
                        <a href="profile.php">👤 Личный кабинет</a>
                        <a href="logout.php">🚪 Выйти</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-ghost">Войти</a>
                <a href="register.php" class="btn btn-primary">Регистрация</a>
            <?php endif; ?>
        </div>

        <button class="burger" id="burger" aria-label="Меню">☰</button>
    </div>
</header>

<?php show_flash(); ?>

<main class="site-main">