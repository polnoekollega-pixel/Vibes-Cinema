<?php
/**
 * Vibes Cinema — админ-панель: просмотр заказов и смена их статусов
 */
$adminTitle = 'Заказы';
include __DIR__ . '/_header.php';

$pdo = db();

// Смена статуса заказа
if (($_GET['status'] ?? '') !== '' && ($_GET['id'] ?? 0) > 0) {
    $status = in_array($_GET['status'], ['new', 'paid', 'cancelled'], true) ? $_GET['status'] : 'new';
    $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([$status, (int)$_GET['id']]);
    flash('success', 'Статус заказа обновлён');
    header('Location: orders.php');
    exit;
}

$orders = $pdo->query(
    'SELECT o.*, u.last_name, u.first_name, u.email, p.name AS plan_name
     FROM orders o
     JOIN users u ON u.id = o.user_id
     JOIN plans p ON p.id = o.plan_id
     ORDER BY o.created_at DESC'
)->fetchAll();
?>

<table class="admin-table">
    <thead><tr><th>№</th><th>Пользователь</th><th>E-mail</th><th>Тариф</th><th>Сумма</th><th>Статус</th><th>Дата</th><th>Действия</th></tr></thead>
    <tbody>
    <?php foreach ($orders as $o): ?>
        <tr>
            <td>#<?= (int)$o['id'] ?></td>
            <td><?= e($o['last_name'] . ' ' . $o['first_name']) ?></td>
            <td><?= e($o['email']) ?></td>
            <td><?= e($o['plan_name']) ?></td>
            <td><?= money($o['amount']) ?></td>
            <td><span class="status-badge <?= $o['status'] ?>"><?= e($o['status']) ?></span></td>
            <td><?= date('d.m.Y H:i', strtotime($o['created_at'])) ?></td>
            <td>
                <a href="orders.php?id=<?= (int)$o['id'] ?>&status=paid" class="btn btn-outline btn-sm" title="Оплачен">✔</a>
                <a href="orders.php?id=<?= (int)$o['id'] ?>&status=cancelled" class="btn btn-danger btn-sm" title="Отменён">✖</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/_footer.php'; ?>