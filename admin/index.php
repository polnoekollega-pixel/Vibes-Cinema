<?php
/**
 * Vibes Cinema — дашборд администратора
 * Сводка по контенту, пользователям, заказам
 */
$adminTitle = 'Дашборд';
include __DIR__ . '/_header.php';

$pdo = db();
$counts = [
    'contents'  => (int)$pdo->query('SELECT COUNT(*) FROM contents')->fetchColumn(),
    'movies'    => (int)$pdo->query('SELECT COUNT(*) FROM contents WHERE type="movie"')->fetchColumn(),
    'series'    => (int)$pdo->query('SELECT COUNT(*) FROM contents WHERE type="series"')->fetchColumn(),
    'users'     => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'subs'      => (int)$pdo->query('SELECT COUNT(*) FROM subscriptions WHERE status="active"')->fetchColumn(),
    'orders'    => (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
    'revenue'   => (float)$pdo->query('SELECT COALESCE(SUM(amount),0) FROM orders WHERE status="paid"')->fetchColumn(),
];
$recentOrders = $pdo->query(
    'SELECT o.*, u.last_name, u.first_name, p.name AS plan_name FROM orders o
     JOIN users u ON u.id = o.user_id JOIN plans p ON p.id = o.plan_id
     ORDER BY o.created_at DESC LIMIT 5'
)->fetchAll();
?>

<div class="stat-grid">
    <div class="stat-card"><span class="stat-icon">🎬</span><div><div class="stat-num"><?= $counts['contents'] ?></div><div class="stat-label">Всего контента</div></div></div>
    <div class="stat-card"><span class="stat-icon">📺</span><div><div class="stat-num"><?= $counts['movies'] ?></div><div class="stat-label">Фильмов</div></div></div>
    <div class="stat-card"><span class="stat-icon">📡</span><div><div class="stat-num"><?= $counts['series'] ?></div><div class="stat-label">Сериалов</div></div></div>
    <div class="stat-card"><span class="stat-icon">👥</span><div><div class="stat-num"><?= $counts['users'] ?></div><div class="stat-label">Пользователей</div></div></div>
    <div class="stat-card"><span class="stat-icon">💎</span><div><div class="stat-num"><?= $counts['subs'] ?></div><div class="stat-label">Активных подписок</div></div></div>
    <div class="stat-card"><span class="stat-icon">💳</span><div><div class="stat-num"><?= money($counts['revenue']) ?></div><div class="stat-label">Выручка</div></div></div>
</div>

<h2 style="margin-top:32px;">Последние заказы</h2>
<table class="admin-table">
    <thead><tr><th>№</th><th>Пользователь</th><th>Тариф</th><th>Сумма</th><th>Статус</th><th>Дата</th></tr></thead>
    <tbody>
    <?php foreach ($recentOrders as $o): ?>
        <tr>
            <td>#<?= (int)$o['id'] ?></td>
            <td><?= e($o['last_name'] . ' ' . $o['first_name']) ?></td>
            <td><?= e($o['plan_name']) ?></td>
            <td><?= money($o['amount']) ?></td>
            <td><span class="status-badge <?= $o['status'] ?>"><?= e($o['status']) ?></span></td>
            <td><?= date('d.m.Y H:i', strtotime($o['created_at'])) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/_footer.php'; ?>