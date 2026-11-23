<?php
/**
 * Vibes Cinema — каталог фильмов и сериалов
 * Фильтрация по типу, категории, сортировка (новинки/популярное/рейтинг)
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cards.php';

$pdo = db();

// Параметры фильтрации
$type = $_GET['type'] ?? '';
$cat  = (int)($_GET['cat'] ?? 0);
$sort = $_GET['sort'] ?? 'new';

$where = [];
$params = [];

if (in_array($type, ['movie', 'series'], true)) {
    $where[] = 'c.type = ?';
    $params[] = $type;
}
if ($cat > 0) {
    $where[] = 'c.id IN (SELECT content_id FROM content_categories WHERE category_id = ?)';
    $params[] = $cat;
}

$sql = 'SELECT c.* FROM contents c';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);

switch ($sort) {
    case 'popular': $sql .= ' ORDER BY c.is_popular DESC, c.id DESC'; break;
    case 'rating':
        $sql .= ' LEFT JOIN (SELECT content_id, AVG(rating) r FROM ratings GROUP BY content_id) rt ON rt.content_id = c.id';
        $sql .= ' ORDER BY rt.r DESC, c.id DESC';
        break;
    default: $sql .= ' ORDER BY c.created_at DESC, c.id DESC';
}

$st = $pdo->prepare($sql);
$st->execute($params);
$items = $st->fetchAll();

$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();

$pageTitle = 'Каталог — Vibes Cinema';
include __DIR__ . '/includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Каталог</h1>
        <p>Фильмы и сериалы на любой вкус</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Фильтры -->
        <div class="filter-bar">
            <div class="filter-group">
                <a href="catalog.php" class="chip <?= $type === '' ? 'active' : '' ?>">Все</a>
                <a href="catalog.php?type=movie" class="chip <?= $type === 'movie' ? 'active' : '' ?>">Фильмы</a>
                <a href="catalog.php?type=series" class="chip <?= $type === 'series' ? 'active' : '' ?>">Сериалы</a>
            </div>
            <div class="filter-group">
                <select id="catSelect" class="chip-select">
                    <option value="0">Все категории</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= $cat === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="sortSelect" class="chip-select">
                    <option value="new" <?= $sort === 'new' ? 'selected' : '' ?>>Сначала новые</option>
                    <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Сначала популярные</option>
                    <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>По рейтингу</option>
                </select>
            </div>
        </div>

        <?php if (empty($items)): ?>
            <div class="empty-state">
                <span class="empty-icon">🎬</span>
                <p>По вашему запросу ничего не найдено. Попробуйте изменить фильтры.</p>
            </div>
        <?php else: ?>
            <div class="cards-row">
                <?php foreach ($items as $item) render_content_card($item); ?>
            </div>
            <p class="count-hint">Найдено: <?= count($items) ?></p>
        <?php endif; ?>
    </div>
</section>

<script>
// Переход по изменению фильтров
document.getElementById('catSelect')?.addEventListener('change', () => {
    const url = new URL(window.location);
    url.searchParams.set('cat', document.getElementById('catSelect').value);
    if (!url.searchParams.get('type')) url.searchParams.set('type', '');
    window.location = url;
});
document.getElementById('sortSelect')?.addEventListener('change', () => {
    const url = new URL(window.location);
    url.searchParams.set('sort', document.getElementById('sortSelect').value);
    window.location = url;
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>