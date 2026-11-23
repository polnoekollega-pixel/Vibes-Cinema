<?php
/**
 * Vibes Cinema — админ-панель: управление тарифами
 */
$adminTitle = 'Тарифы';
include __DIR__ . '/_header.php';

$pdo = db();

// Удаление
if (($_GET['del'] ?? 0) > 0) {
    $pdo->prepare('DELETE FROM plans WHERE id = ?')->execute([(int)$_GET['del']]);
    flash('success', 'Тариф удалён');
    header('Location: plans.php');
    exit;
}

// Сохранение
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'name'           => trim($_POST['name'] ?? ''),
        'price'          => (float)($_POST['price'] ?? 0),
        'months'         => (int)($_POST['months'] ?? 1),
        'quality'        => trim($_POST['quality'] ?? 'HD'),
        'devices'        => (int)($_POST['devices'] ?? 1),
        'features'       => trim($_POST['features'] ?? ''),
        'is_recommended' => isset($_POST['is_recommended']) ? 1 : 0,
    ];
    if ($data['name'] === '') {
        flash('error', 'Укажите название тарифа');
    } elseif ($id > 0) {
        $pdo->prepare('UPDATE plans SET name=?, price=?, months=?, quality=?, devices=?, features=?, is_recommended=? WHERE id=?')
            ->execute(array_merge(array_values($data), [$id]));
        flash('success', 'Тариф обновлён');
    } else {
        $pdo->prepare('INSERT INTO plans (name, price, months, quality, devices, features, is_recommended) VALUES (?,?,?,?,?,?,?)')
            ->execute(array_values($data));
        flash('success', 'Тариф добавлен');
    }
    header('Location: plans.php');
    exit;
}

$items = $pdo->query('SELECT * FROM plans ORDER BY price')->fetchAll();
$edit = null;
if (($_GET['edit'] ?? 0) > 0) {
    $st = $pdo->prepare('SELECT * FROM plans WHERE id = ?');
    $st->execute([(int)$_GET['edit']]);
    $edit = $st->fetch();
}
?>

<h3 style="margin-top:8px;"><?= $edit ? '✏️ Редактировать тариф' : '➕ Добавить тариф' ?></h3>
<form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">
    <div class="form-row">
        <div class="form-field"><label>Название</label><input type="text" name="name" value="<?= e($edit['name'] ?? '') ?>" required></div>
        <div class="form-field"><label>Цена (₽/мес)</label><input type="number" step="0.01" name="price" value="<?= e($edit['price'] ?? '') ?>" required></div>
        <div class="form-field"><label>Месяцев</label><input type="number" name="months" value="<?= e($edit['months'] ?? '1') ?>"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>Качество</label><input type="text" name="quality" value="<?= e($edit['quality'] ?? 'HD') ?>"></div>
        <div class="form-field"><label>Устройств</label><input type="number" name="devices" value="<?= e($edit['devices'] ?? '1') ?>"></div>
    </div>
    <div class="form-field"><label>Возможности (через ;)</label><input type="text" name="features" value="<?= e($edit['features'] ?? '') ?>" placeholder="Доступ к библиотеке; Без рекламы"></div>
    <label class="check-row"><input type="checkbox" name="is_recommended" <?= $edit && $edit['is_recommended'] ? 'checked' : '' ?>> Рекомендуемый</label>
    <br><br>
    <button type="submit" class="btn btn-primary"><?= $edit ? 'Сохранить' : 'Добавить' ?></button>
    <?php if ($edit): ?><a href="plans.php" class="btn btn-ghost">Отмена</a><?php endif; ?>
</form>

<table class="admin-table">
    <thead><tr><th>ID</th><th>Название</th><th>Цена</th><th>Месяцев</th><th>Качество</th><th>Устройств</th><th>Реком.</th><th>Действия</th></tr></thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= (int)$item['id'] ?></td>
            <td><?= e($item['name']) ?></td>
            <td><?= money($item['price']) ?></td>
            <td><?= (int)$item['months'] ?></td>
            <td><?= e($item['quality']) ?></td>
            <td><?= (int)$item['devices'] ?></td>
            <td><?= $item['is_recommended'] ? '✅' : '—' ?></td>
            <td>
                <a href="plans.php?edit=<?= (int)$item['id'] ?>" class="btn btn-outline btn-sm">✏️</a>
                <a href="plans.php?del=<?= (int)$item['id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Удалить тариф?');">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/_footer.php'; ?>