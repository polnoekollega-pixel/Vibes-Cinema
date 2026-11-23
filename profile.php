<?php
/**
 * Vibes Cinema — личный кабинет пользователя
 * Доступен только авторизованным. Содержит: данные профиля,
 * историю просмотра, избранное, подписку
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cards.php';
require_login();

$pdo = db();
$user = current_user();

// Смена пароля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'change_pass') {
    csrf_check();
    $cur = $_POST['current'] ?? '';
    $new = $_POST['new'] ?? '';
    $new2 = $_POST['new2'] ?? '';
    if ($new === '' || mb_strlen($new) < 6) {
        flash('error', 'Новый пароль должен содержать не менее 6 символов');
    } elseif ($new !== $new2) {
        flash('error', 'Новые пароли не совпадают');
    } else {
        // Проверяем текущий пароль
        $st = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
        $st->execute([$user['id']]);
        $hash = $st->fetchColumn();
        if (!password_verify($cur, $hash)) {
            flash('error', 'Текущий пароль введён неверно');
        } else {
            $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);
            flash('success', 'Пароль успешно изменён');
        }
    }
}

// Избранное
$st = $pdo->prepare(
    'SELECT c.* FROM contents c JOIN favorites f ON f.content_id = c.id WHERE f.user_id = ? ORDER BY f.created_at DESC'
);
$st->execute([$user['id']]);
$favorites = $st->fetchAll();

// История просмотра
$st = $pdo->prepare(
    'SELECT c.*, w.watched_at FROM contents c JOIN watch_history w ON w.content_id = c.id
     WHERE w.user_id = ? ORDER BY w.watched_at DESC LIMIT 20'
);
$st->execute([$user['id']]);
$history = $st->fetchAll();

// Текущая подписка
$st = $pdo->prepare(
    'SELECT s.*, p.name AS plan_name, p.price AS plan_price FROM subscriptions s
     JOIN plans p ON p.id = s.plan_id
     WHERE s.user_id = ? AND s.status = "active" AND s.expires_at > NOW()
     ORDER BY s.expires_at DESC LIMIT 1'
);
$st->execute([$user['id']]);
$activeSub = $st->fetch();

$pageTitle = 'Личный кабинет — Vibes Cinema';
include __DIR__ . '/includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Личный кабинет</h1>
        <p>Здравствуйте, <?= e($user['last_name'] . ' ' . $user['first_name']) ?>!</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="profile-layout">

            <!-- Блок подписки -->
            <aside class="profile-side">
                <div class="side-card">
                    <h3>💳 Подписка</h3>
                    <?php if ($activeSub): ?>
                        <div class="sub-active">
                            <span class="sub-plan"><?= e($activeSub['plan_name']) ?></span>
                            <span class="sub-status active">Активна</span>
                            <p class="sub-exp">Действует до <strong><?= date('d.m.Y', strtotime($activeSub['expires_at'])) ?></strong></p>
                        </div>
                    <?php else: ?>
                        <div class="sub-none">
                            <p>У вас нет активной подписки.</p>
                            <a href="plans.php" class="btn btn-primary btn-block">Оформить подписку</a>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <h3>📊 Мои данные</h3>
                    <ul class="profile-info">
                        <li><span>ФИО</span> <?= e($user['last_name'] . ' ' . $user['first_name'] . ' ' . $user['middle_name']) ?></li>
                        <li><span>Логин</span> <?= e($user['login']) ?></li>
                        <li><span>E-mail</span> <?= e($user['email']) ?></li>
                        <li><span>Роль</span> <?= $user['role'] === 'admin' ? 'Администратор' : ($user['role'] === 'subscriber' ? 'Подписчик' : 'Пользователь') ?></li>
                    </ul>
                </div>

                <!-- Смена пароля -->
                <div class="side-card">
                    <h3>🔒 Сменить пароль</h3>
                    <form method="post">
                        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                        <input type="hidden" name="action" value="change_pass">
                        <div class="form-field">
                            <label for="current">Текущий пароль</label>
                            <input type="password" id="current" name="current" placeholder="••••••••">
                        </div>
                        <div class="form-field">
                            <label for="new">Новый пароль</label>
                            <input type="password" id="new" name="new" placeholder="••••••••">
                        </div>
                        <div class="form-field">
                            <label for="new2">Повторите новый пароль</label>
                            <input type="password" id="new2" name="new2" placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn btn-outline btn-block">Обновить пароль</button>
                    </form>
                </div>
            </aside>

            <!-- Избранное -->
            <div class="profile-main">
                <h2 class="section-title">❤️ Моё избранное</h2>
                <?php if (empty($favorites)): ?>
                    <div class="empty-state small">
                        <p>Пока пусто. Добавляйте фильмы и сериалы в избранное, нажимая на ♡.</p>
                        <a href="catalog.php" class="btn btn-primary">Перейти в каталог</a>
                    </div>
                <?php else: ?>
                    <div class="cards-row">
                        <?php foreach ($favorites as $f) render_content_card($f); ?>
                    </div>
                <?php endif; ?>

                <h2 class="section-title">🕘 История просмотра</h2>
                <?php if (empty($history)): ?>
                    <div class="empty-state small">
                        <p>История просмотра пуста.</p>
                    </div>
                <?php else: ?>
                    <div class="history-list">
                        <?php foreach ($history as $h): ?>
                            <a href="content.php?id=<?= (int)$h['id'] ?>" class="history-item">
                                <img src="<?= e($h['poster_url']) ?>" alt="<?= e($h['title']) ?>" class="history-thumb">
                                <span class="history-name"><?= e($h['title']) ?></span>
                                <span class="history-when"><?= date('d.m.Y H:i', strtotime($h['watched_at'])) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>