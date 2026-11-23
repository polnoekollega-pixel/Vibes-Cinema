<?php
/**
 * Vibes Cinema — админ-панель: управление категориями контента
 */
$adminTitle = 'Управление категориями';
include __DIR__ . '/_header.php';

$pdo = db();

// Удаление
if (($_GET['del'] ?? 0) > 0) {
    $pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([(int)$_GET['del']]);
    flash('success', 'Категория удалена');
    header('Location: categories.php');
    exit;
}

// Сохранение
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $icon = trim($_POST['icon'] ?? '🎬');
    $desc = trim($_POST['description'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if ($slug === '') {
        $slug = strtolower(str_replace([' ', 'ь'], ['-', ''], $name));
    }
    if ($name === '') {
        flash('error', 'Укажите название категории');
    } elseif ($id > 0) {
        $pdo->prepare('UPDATE categories SET name=?, slug=?, icon=?, description=? WHERE id=?')
            ->execute([$name, $slug, $icon, $desc, $id]);
        flash('success', 'Категория обновлена');
    } else {
        $pdo->prepare('INSERT INTO categories (name, slug, icon, description) VALUES (?,?,?,?)')
            ->execute([$name, $slug, $icon, $desc]);
        flash('success', 'Категория добавлена');
    }
    header('Location: categories.php');
    exit;
}

$items = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();
$edit = null;
if (($_GET['edit'] ?? 0) > 0) {
    $st = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $st->execute([(int)$_GET['edit']]);
    $edit = $st->fetch();
}
?>

<h3 style="margin-top:8px;"><?= $edit ? '✏️ Редактировать категорию' : '➕ Добавить категорию' ?></h3>
<form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">
    <div class="form-row">
        <div class="form-field"><label>Иконка</label><input type="text" name="icon" value="<?= e($edit['icon'] ?? '🎬') ?>"></div>
        <div class="form-field"><label>Название</label><input type="text" name="name" value="<?= e($edit['name'] ?? '') ?>" required></div>
        <div class="form-field"><label>Slug</label><input type="text" name="slug" value="<?= e($edit['slug'] ?? '') ?>" placeholder="оставьте пустым для авто"></div>
    </div>
    <div class="form-field"><label>Описание</label><input type="text" name="description" value="<?= e($edit['description'] ?? '') ?>"></div>
    <button type="submit" class="btn btn-primary"><?= $edit ? 'Сохранить' : 'Добавить' ?></button>
    <?php if ($edit): ?><a href="categories.php" class="btn btn-ghost">Отмена</a><?php endif; ?>
</form>

<table class="admin-table">
    <thead><tr><th>ID</th><th>Иконка</th><th>Название</th><th>Slug</th><th>Описание</th><th>Действия</th></tr></thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= (int)$item['id'] ?></td>
            <td style="font-size:20px;"><?= e($item['icon']) ?></td>
            <td><?= e($item['name']) ?></td>
            <td><?= e($item['slug']) ?></td>
            <td><?= e($item['description']) ?></td>
            <td>
                <a href="categories.php?edit=<?= (int)$item['id'] ?>" class="btn btn-outline btn-sm">✏️</a>
                <a href="categories.php?del=<?= (int)$item['id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Удалить категорию?');">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/_footer.php'; ?>