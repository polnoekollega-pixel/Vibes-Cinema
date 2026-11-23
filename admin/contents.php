<?php
/**
 * Vibes Cinema — админ-панель: управление контентом (добавление, удаление, редактирование)
 */
$adminTitle = 'Управление контентом';
include __DIR__ . '/_header.php';

$pdo = db();

// Удаление
if (($_GET['del'] ?? 0) > 0) {
    $pdo->prepare('DELETE FROM contents WHERE id = ?')->execute([(int)$_GET['del']]);
    flash('success', 'Контент удалён');
    header('Location: contents.php');
    exit;
}

// Сохранение (добавление или изменение)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'title'       => trim($_POST['title'] ?? ''),
        'type'        => $_POST['type'] === 'series' ? 'series' : 'movie',
        'description' => trim($_POST['description'] ?? ''),
        'release_year'=> (int)($_POST['release_year'] ?? 0) ?: null,
        'age_limit'   => trim($_POST['age_limit'] ?? '16+'),
        'duration_min'=> (int)($_POST['duration_min'] ?? 0) ?: null,
        'seasons'     => (int)($_POST['seasons'] ?? 0) ?: null,
        'director'    => trim($_POST['director'] ?? ''),
        'actors'      => trim($_POST['actors'] ?? ''),
        'trailer_url' => trim($_POST['trailer_url'] ?? ''),
        'poster_url'  => trim($_POST['poster_url'] ?? ''),
        'banner_url'  => trim($_POST['banner_url'] ?? ''),
        'is_new'      => isset($_POST['is_new']) ? 1 : 0,
        'is_popular'  => isset($_POST['is_popular']) ? 1 : 0,
        'price'       => (float)($_POST['price'] ?? 0),
    ];

    if ($data['title'] === '' || $data['description'] === '') {
        flash('error', 'Заполните название и описание');
        header('Location: contents.php?edit=' . $id);
        exit;
    }

    if ($id > 0) {
        $sql = 'UPDATE contents SET title=?, type=?, description=?, release_year=?, age_limit=?, duration_min=?,
                seasons=?, director=?, actors=?, trailer_url=?, poster_url=?, banner_url=?, is_new=?, is_popular=?, price=?
                WHERE id=?';
        $pdo->prepare($sql)->execute(array_merge(array_values($data), [$id]));
        flash('success', 'Контент обновлён');
    } else {
        $sql = 'INSERT INTO contents (title, type, description, release_year, age_limit, duration_min, seasons,
                director, actors, trailer_url, poster_url, banner_url, is_new, is_popular, price)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $pdo->prepare($sql)->execute(array_values($data));
        flash('success', 'Контент добавлен');
    }
    header('Location: contents.php');
    exit;
}

$items = $pdo->query('SELECT * FROM contents ORDER BY id DESC')->fetchAll();

// Контент для редактирования
$edit = null;
if (($_GET['edit'] ?? 0) > 0) {
    $st = $pdo->prepare('SELECT * FROM contents WHERE id = ?');
    $st->execute([(int)$_GET['edit']]);
    $edit = $st->fetch();
}
?>

<?php if ($edit): ?>
<h3 style="margin-top:8px;">✏️ Редактирование: <?= e($edit['title']) ?></h3>
<?php else: ?>
<h3 style="margin-top:8px;">➕ Добавить контент</h3>
<?php endif; ?>

<form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">
    <div class="form-row">
        <div class="form-field" style="flex:2;">
            <label>Название</label>
            <input type="text" name="title" value="<?= e($edit['title'] ?? '') ?>" required>
        </div>
        <div class="form-field">
            <label>Тип</label>
            <select name="type">
                <option value="movie" <?= !$edit || $edit['type'] === 'movie' ? 'selected' : '' ?>>Фильм</option>
                <option value="series" <?= $edit && $edit['type'] === 'series' ? 'selected' : '' ?>>Сериал</option>
            </select>
        </div>
    </div>
    <div class="form-field">
        <label>Описание</label>
        <textarea name="description" rows="3" required><?= e($edit['description'] ?? '') ?></textarea>
    </div>
    <div class="form-row">
        <div class="form-field"><label>Год</label><input type="number" name="release_year" value="<?= e($edit['release_year'] ?? '') ?>" placeholder="2026"></div>
        <div class="form-field"><label>Возрастной рейтинг</label><input type="text" name="age_limit" value="<?= e($edit['age_limit'] ?? '16+') ?>"></div>
        <div class="form-field"><label>Длительность (мин)</label><input type="number" name="duration_min" value="<?= e($edit['duration_min'] ?? '') ?>"></div>
        <div class="form-field"><label>Сезонов (для сериалов)</label><input type="number" name="seasons" value="<?= e($edit['seasons'] ?? '') ?>"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>Режиссёр</label><input type="text" name="director" value="<?= e($edit['director'] ?? '') ?>"></div>
        <div class="form-field"><label>Актёры (через запятую)</label><input type="text" name="actors" value="<?= e($edit['actors'] ?? '') ?>"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>Трейлер (URL)</label><input type="text" name="trailer_url" value="<?= e($edit['trailer_url'] ?? '') ?>" placeholder="https://www.youtube.com/embed/..."></div>
        <div class="form-field"><label>Цена (₽, 0 — бесплатно)</label><input type="number" step="0.01" name="price" value="<?= e($edit['price'] ?? '0') ?>"></div>
    </div>
    <div class="form-row">
        <div class="form-field"><label>Постер (URL)</label><input type="text" name="poster_url" value="<?= e($edit['poster_url'] ?? '') ?>" placeholder="https://..."></div>
        <div class="form-field"><label>Баннер (URL)</label><input type="text" name="banner_url" value="<?= e($edit['banner_url'] ?? '') ?>" placeholder="https://..."></div>
    </div>
    <div class="form-row" style="align-items:center;">
        <label class="check-row"><input type="checkbox" name="is_new" <?= $edit && $edit['is_new'] ? 'checked' : '' ?>> Новинка</label>
        <label class="check-row"><input type="checkbox" name="is_popular" <?= $edit && $edit['is_popular'] ? 'checked' : '' ?>> Популярное</label>
        <button type="submit" class="btn btn-primary"><?= $edit ? 'Сохранить' : 'Добавить' ?></button>
        <?php if ($edit): ?><a href="contents.php" class="btn btn-ghost">Отмена</a><?php endif; ?>
    </div>
</form>

<table class="admin-table">
    <thead><tr><th>ID</th><th>Постер</th><th>Название</th><th>Тип</th><th>Год</th><th>Цена</th><th>Новинка</th><th>Попул.</th><th>Действия</th></tr></thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= (int)$item['id'] ?></td>
            <td><img src="<?= e($item['poster_url']) ?>" class="table-thumb" alt=""></td>
            <td><?= e($item['title']) ?></td>
            <td><?= $item['type'] === 'series' ? 'Сериал' : 'Фильм' ?></td>
            <td><?= (int)$item['release_year'] ?></td>
            <td><?= money($item['price']) ?></td>
            <td><?= $item['is_new'] ? '✅' : '—' ?></td>
            <td><?= $item['is_popular'] ? '✅' : '—' ?></td>
            <td>
                <a href="contents.php?edit=<?= (int)$item['id'] ?>" class="btn btn-outline btn-sm">✏️</a>
                <a href="contents.php?del=<?= (int)$item['id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Удалить «<?= e($item['title']) ?>»?');">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/_footer.php'; ?>