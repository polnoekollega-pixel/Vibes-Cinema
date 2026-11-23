<?php
/**
 * Vibes Cinema — поиск по фильмам и сериалам
 * Ищет по названию, описанию, режиссёру и актёрам
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cards.php';

$q = trim($_GET['q'] ?? '');
$items = [];

if ($q !== '') {
    $pdo = db();
    $like = '%' . $q . '%';
    $st = $pdo->prepare(
        'SELECT DISTINCT c.* FROM contents c
         WHERE c.title LIKE ? OR c.description LIKE ? OR c.director LIKE ? OR c.actors LIKE ?
         ORDER BY c.title LIMIT 40'
    );
    $st->execute([$like, $like, $like, $like]);
    $items = $st->fetchAll();
}

$pageTitle = 'Поиск: ' . ($q ?: '— Vibes Cinema');
include __DIR__ . '/includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Поиск</h1>
        <form class="search-big" action="search.php" method="get">
            <input type="search" name="q" placeholder="Введите название фильма или сериала…" value="<?= e($q) ?>" autofocus>
            <button type="submit" class="btn btn-primary">Найти</button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($q === ''): ?>
            <div class="empty-state">
                <span class="empty-icon">🔍</span>
                <p>Введите запрос, чтобы найти фильмы и сериалы.</p>
            </div>
        <?php elseif (empty($items)): ?>
            <div class="empty-state">
                <span class="empty-icon">😕</span>
                <p>По запросу «<?= e($q) ?>» ничего не найдено.</p>
            </div>
        <?php else: ?>
            <h2 class="section-title">Результаты по запросу «<?= e($q) ?>» (<?= count($items) ?>)</h2>
            <div class="cards-row">
                <?php foreach ($items as $item) render_content_card($item); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>