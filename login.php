<?php
/**
 * Vibes Cinema — авторизация пользователя
 * Работает через сессии. Поддерживает роли: администратор, подписчик, пользователь
 */
require_once __DIR__ . '/includes/functions.php';

// Уже авторизован — на главную
if (current_user()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$oldLogin = '';

// Обработка отправки формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $oldLogin = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($oldLogin === '' || $password === '') {
        $errors['general'] = 'Заполните все поля';
    } else {
        // Поиск пользователя по логину или e-mail
        $st = db()->prepare('SELECT * FROM users WHERE login = ? OR email = ?');
        $st->execute([$oldLogin, $oldLogin]);
        $user = $st->fetch();

        // Проверка пароля
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'         => (int)$user['id'],
                'last_name'  => $user['last_name'],
                'first_name' => $user['first_name'],
                'middle_name'=> $user['middle_name'],
                'login'      => $user['login'],
                'email'      => $user['email'],
                'role'       => $user['role'],
            ];
            // Администратора отправляем в админ-панель
            header('Location: ' . ($user['role'] === 'admin' ? 'admin/index.php' : 'index.php'));
            exit;
        }
        $errors['general'] = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — Vibes Cinema</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
    <meta name="theme-color" content="#0b0d17">
</head>
<body class="auth-page">
    <div class="auth-wrap">
        <a href="index.php" class="auth-logo">✨ Vibes Cinema</a>
        <div class="auth-card">
            <h1>С возвращением!</h1>
            <p class="auth-sub">Войдите, чтобы продолжить просмотр</p>

            <?php if (!empty($errors['general'])): ?>
                <div class="flash flash-error"><?= e($errors['general']) ?></div>
            <?php endif; ?>
            <?php show_flash(); ?>

            <form method="post" id="loginForm" novalidate>
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <div class="form-field">
                    <label for="login">Логин или e-mail</label>
                    <input type="text" id="login" name="login" value="<?= e($oldLogin) ?>" placeholder="ivanov или ivanov@mail.ru" autofocus>
                    <span class="field-error" id="err-login"></span>
                </div>
                <div class="form-field">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" placeholder="••••••••">
                    <span class="field-error" id="err-password"></span>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Войти</button>
            </form>

            <p class="auth-alt">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>

            <div class="auth-demo">
                <strong>Демо-доступ:</strong><br>
                Администратор — <code>admin</code> / <code>12345678</code><br>
                Подписчик — <code>ivanov</code> / <code>12345678</code><br>
                Пользователь — <code>petrova</code> / <code>12345678</code>
            </div>
        </div>
    </div>
    <script src="assets/js/auth.js"></script>
</body>
</html>