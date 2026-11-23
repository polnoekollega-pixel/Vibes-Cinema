<?php
/**
 * Vibes Cinema — регистрация нового пользователя
 * Принимает данные формы, проверяет валидность, сохраняет в базу данных
 */
require_once __DIR__ . '/includes/functions.php';

// Если пользователь уже вошёл — отправляем на главную
if (current_user()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$old = ['last_name' => '', 'first_name' => '', 'middle_name' => '', 'login' => '', 'email' => ''];

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    // Собираем и чистим входные данные
    $old = [
        'last_name'   => trim($_POST['last_name'] ?? ''),
        'first_name'  => trim($_POST['first_name'] ?? ''),
        'middle_name' => trim($_POST['middle_name'] ?? ''),
        'login'       => trim($_POST['login'] ?? ''),
        'email'       => trim($_POST['email'] ?? ''),
    ];
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $agree = isset($_POST['agree']);

    // Валидация полей
    if ($old['last_name'] === '')  $errors['last_name'] = 'Укажите фамилию';
    if ($old['first_name'] === '') $errors['first_name'] = 'Укажите имя';
    if (mb_strlen($old['login']) < 3) $errors['login'] = 'Логин должен содержать не менее 3 символов';
    if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $old['login'])) $errors['login'] = 'Логин может содержать только латинские буквы, цифры и _ . -';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Введите корректный e-mail';
    if (mb_strlen($password) < 6) $errors['password'] = 'Пароль должен содержать не менее 6 символов';
    if ($password !== $password2) $errors['password2'] = 'Пароли не совпадают';
    if (!$agree) $errors['agree'] = 'Необходимо согласие с правилами регистрации';

    // Проверка уникальности логина и e-mail
    if (empty($errors)) {
        $st = db()->prepare('SELECT id FROM users WHERE login = ? OR email = ?');
        $st->execute([$old['login'], $old['email']]);
        if ($st->fetch()) {
            $errors['login'] = 'Пользователь с таким логином или e-mail уже существует';
        }
    }

    // Сохраняем пользователя
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $st = db()->prepare(
            'INSERT INTO users (last_name, first_name, middle_name, login, email, password_hash, role)
             VALUES (?, ?, ?, ?, ?, ?, "user")'
        );
        $st->execute([$old['last_name'], $old['first_name'], $old['middle_name'] ?: null, $old['login'], $old['email'], $hash]);

        flash('success', 'Регистрация прошла успешно! Теперь вы можете войти.');
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация — Vibes Cinema</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
    <meta name="theme-color" content="#0b0d17">
</head>
<body class="auth-page">
    <div class="auth-wrap">
        <a href="index.php" class="auth-logo">✨ Vibes Cinema</a>
        <div class="auth-card">
            <h1>Создать аккаунт</h1>
            <p class="auth-sub">Присоединяйтесь к Vibes Cinema — регистрация займёт минуту</p>

            <?php show_flash(); ?>

            <form method="post" id="regForm" novalidate>
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="last_name">Фамилия *</label>
                        <input type="text" id="last_name" name="last_name" value="<?= e($old['last_name']) ?>" placeholder="Иванов">
                        <span class="field-error" id="err-last_name"><?= e($errors['last_name'] ?? '') ?></span>
                    </div>
                    <div class="form-field">
                        <label for="first_name">Имя *</label>
                        <input type="text" id="first_name" name="first_name" value="<?= e($old['first_name']) ?>" placeholder="Иван">
                        <span class="field-error" id="err-first_name"><?= e($errors['first_name'] ?? '') ?></span>
                    </div>
                    <div class="form-field">
                        <label for="middle_name">Отчество</label>
                        <input type="text" id="middle_name" name="middle_name" value="<?= e($old['middle_name']) ?>" placeholder="Иванович">
                        <span class="field-error" id="err-middle_name"></span>
                    </div>
                    <div class="form-field">
                        <label for="login">Логин *</label>
                        <input type="text" id="login" name="login" value="<?= e($old['login']) ?>" placeholder="ivanov_2026">
                        <span class="field-error" id="err-login"><?= e($errors['login'] ?? '') ?></span>
                    </div>
                    <div class="form-field">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" placeholder="ivan@mail.ru">
                        <span class="field-error" id="err-email"><?= e($errors['email'] ?? '') ?></span>
                    </div>
                    <div class="form-field">
                        <label for="password">Пароль *</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                        <span class="field-error" id="err-password"><?= e($errors['password'] ?? '') ?></span>
                    </div>
                    <div class="form-field">
                        <label for="password2">Повторите пароль *</label>
                        <input type="password" id="password2" name="password2" placeholder="••••••••">
                        <span class="field-error" id="err-password2"><?= e($errors['password2'] ?? '') ?></span>
                    </div>
                </div>
                <label class="check-row">
                    <input type="checkbox" name="agree" id="agree" <?= isset($_POST['agree']) ? 'checked' : '' ?>>
                    <span>Я принимаю условия <a href="#" onclick="return false;">правил регистрации</a> и политику конфиденциальности *</span>
                </label>
                <span class="field-error" id="err-agree"><?= e($errors['agree'] ?? '') ?></span>

                <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
            </form>

            <p class="auth-alt">Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </div>
    </div>
    <script src="assets/js/auth.js"></script>
</body>
</html>