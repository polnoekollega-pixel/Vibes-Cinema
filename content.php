<?php
/**
 * Vibes Cinema — страница фильма или сериала
 * Содержит: описание и оценки, список актёров и режиссёров,
 * трейлер, список похожих картин, для сериалов — сезоны и эпизоды
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cards.php';

$pdo = db();
$id = (int)($_GET['id'] ?? 0);

// Загрузка контента
$st = $pdo->prepare('SELECT * FROM contents WHERE id = ?');
$st->execute([$id]);
$item = $st->fetch();

// Если контент не найден — 404
if (!$item) {
    http_response_code(404);
    $pageTitle = 'Не найдено — Vibes Cinema';
    include __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="container"><div class="empty-state"><span class="empty-icon">🎬</span><p>Контент не найден.</p><a href="index.php" class="btn btn-primary">На главную</a></div></div></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Сохранение в историю просмотра (только для авторизованных)
$user = current_user();
if ($user) {
    $st = $pdo->prepare('INSERT IGNORE INTO watch_history (user_id, content_id, watched_at) VALUES (?, ?, NOW())');
    $st->execute([$user['id'], $id]);
}

// Категории контента
$st = $pdo->prepare(
    'SELECT c.* FROM categories c JOIN content_categories cc ON cc.category_id = c.id WHERE cc.content_id = ?'
);
$st->execute([$id]);
$itemCats = $st->fetchAll();

// Средняя оценка и оценки пользователя
$rating = avg_rating($id);
$myRating = null;
if ($user) {
    $st = $pdo->prepare('SELECT rating FROM ratings WHERE user_id = ? AND content_id = ?');
    $st->execute([$user['id'], $id]);
    $myRating = $st->fetchColumn();
}

// Похожие: та же категория, исключая текущий
$st = $pdo->prepare(
    'SELECT DISTINCT c.* FROM contents c
     JOIN content_categories cc ON cc.content_id = c.id
     WHERE cc.category_id IN (SELECT category_id FROM content_categories WHERE content_id = ?)
       AND c.id != ?
     LIMIT 6'
);
$st->execute([$id, $id]);
$similar = $st->fetchAll();

$isSeries = $item['type'] === 'series';

// Сезоны и серии из базы (реальные данные)
$seasonsList = [];
if ($isSeries) {
    $st = $pdo->prepare(
        'SELECT s.id AS season_id, s.number AS season_number, e.number AS episode_number, e.title, e.duration_min
         FROM seasons s LEFT JOIN episodes e ON e.season_id = s.id
         WHERE s.content_id = ?
         ORDER BY s.number, e.number'
    );
    $st->execute([$id]);
    foreach ($st->fetchAll() as $row) {
        $sid = (int)$row['season_id'];
        if (!isset($seasonsList[$sid])) {
            $seasonsList[$sid] = ['number' => (int)$row['season_number'], 'episodes' => []];
        }
        if ($row['episode_number'] !== null) {
            $seasonsList[$sid]['episodes'][] = [
                'number'   => (int)$row['episode_number'],
                'title'    => $row['title'],
                'duration' => (int)$row['duration_min'],
            ];
        }
    }
}

// Итоги: сколько всего серий и средняя длина серии
$episodesTotal = 0;
$durationSum = 0;
$durationCount = 0;
foreach ($seasonsList as $season) {
    foreach ($season['episodes'] as $ep) {
        $episodesTotal++;
        if ($ep['duration'] > 0) {
            $durationSum += $ep['duration'];
            $durationCount++;
        }
    }
}
$avgEpisodeLen = $durationCount ? (int)round($durationSum / $durationCount) : 0;

$seasonsLabel = (int)$item['seasons'] . ' ' . plural_ru((int)$item['seasons'], 'сезон', 'сезона', 'сезонов');
$episodesLabel = $episodesTotal . ' ' . plural_ru($episodesTotal, 'серия', 'серии', 'серий');

$navType = $isSeries ? 'series' : 'movies';
$pageTitle = $item['title'] . ' — Vibes Cinema';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero с баннером контента -->
<section class="content-hero" style="background-image: url('<?= e($item['banner_url'] ?: $item['poster_url']) ?>');">
    <div class="content-hero-overlay"></div>
    <div class="container content-hero-inner">
        <div class="content-poster">
            <img src="<?= e($item['poster_url']) ?>" alt="<?= e($item['title']) ?>">
        </div>
        <div class="content-info">
            <div class="content-badges">
                <span class="badge-type"><?= $isSeries ? 'Сериал' : 'Фильм' ?></span>
                <span class="badge-year"><?= (int)$item['release_year'] ?></span>
                <span class="badge-age"><?= e($item['age_limit']) ?></span>
                <?php foreach ($itemCats as $c): ?>
                    <a href="catalog.php?cat=<?= (int)$c['id'] ?>" class="badge-cat"><?= e($c['name']) ?></a>
                <?php endforeach; ?>
            </div>
            <h1 class="content-title"><?= e($item['title']) ?></h1>
            <div class="content-meta">
                <span class="content-rating">⭐ <?= $rating ? number_format($rating, 1, '.', '') : '—' ?></span>
                <?php if ($isSeries): ?>
                    <span>📺 <?= $seasonsLabel ?><?= $episodesTotal ? ' · ' . $episodesLabel : '' ?></span>
                <?php else: ?>
                    <span>⏱ <?= (int)$item['duration_min'] ?> мин</span>
                <?php endif; ?>
                <?php $actorsCount = (int)max(1, count(explode(',', (string)$item['actors']))); ?>
                <span>👥 <?= $actorsCount ?> <?= plural_ru($actorsCount, 'актёр', 'актёра', 'актёров') ?></span>
            </div>
            <p class="content-desc"><?= e($item['description']) ?></p>

            <div class="content-actions">
                <a href="#trailer" class="btn btn-primary btn-lg">▶ Смотреть трейлер</a>
                <button class="btn btn-ghost btn-lg" data-fav-big="<?= (int)$id ?>">
                    <?= in_favorites($id) ? '♥ В избранном' : '♡ В избранное' ?>
                </button>
            </div>

            <!-- Оценка пользователя -->
            <div class="rating-box">
                <span class="rating-label">Ваша оценка:</span>
                <span class="stars" id="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star-rate <?= $myRating && $i <= $myRating ? 'filled' : '' ?>" data-val="<?= $i ?>">★</span>
                    <?php endfor; ?>
                </span>
                <span class="rating-result" id="ratingResult">
                    <?= $myRating ? 'Вы оценили на ' . $myRating : (current_user() ? 'Оцените фильм' : '') ?>
                </span>
            </div>
        </div>
    </div>
</section>

<!-- О контенте -->
<section class="section">
    <div class="container">
        <div class="content-details">
            <div class="detail-block">
                <h3>О <?= $isSeries ? 'сериале' : 'фильме' ?></h3>
                <table class="detail-table">
                    <tr><td>Режиссёр</td><td><?= e($item['director'] ?: '—') ?></td></tr>
                    <tr><td>В ролях</td><td><?= e($item['actors'] ?: '—') ?></td></tr>
                    <tr><td>Год выпуска</td><td><?= (int)$item['release_year'] ?></td></tr>
                    <tr><td>Продолжительность</td><td><?php if ($isSeries): ?><?= $seasonsLabel ?><?= $episodesTotal ? ' · ' . $episodesLabel : '' ?><?= $avgEpisodeLen ? ' · ~' . $avgEpisodeLen . ' мин/серия' : '' ?><?php else: ?><?= (int)$item['duration_min'] ?> <?= plural_ru((int)$item['duration_min'], 'минута', 'минуты', 'минут') ?><?php endif; ?></td></tr>
                    <tr><td>Возрастной рейтинг</td><td><?= e($item['age_limit']) ?></td></tr>
                </table>
            </div>

            <?php if ($isSeries): ?>
            <!-- Сезоны и эпизоды -->
            <div class="detail-block">
                <h3>Сезоны и эпизоды</h3>
                <?php if (empty($seasonsList)): ?>
                    <p class="muted">Список серий пока не заполнен.</p>
                <?php else: ?>
                    <?php $firstSeason = true; foreach ($seasonsList as $season): ?>
                        <?php
                        $seasonEps = $season['episodes'];
                        $seasonDur = array_filter(array_column($seasonEps, 'duration'));
                        $seasonAvg = $seasonDur ? (int)round(array_sum($seasonDur) / count($seasonDur)) : 0;
                        $seasonEpsLabel = count($seasonEps) . ' ' . plural_ru(count($seasonEps), 'серия', 'серии', 'серий');
                        ?>
                        <details class="season-block" <?= $firstSeason ? 'open' : '' ?>>
                            <summary>Сезон <?= $season['number'] ?> · <?= $seasonEpsLabel ?></summary>
                            <?php if ($seasonAvg): ?><p class="season-desc">Средний хронометраж серии — <?= $seasonAvg ?> мин</p><?php endif; ?>
                            <div class="episode-list">
                                <?php foreach ($seasonEps as $ep): ?>
                                    <div class="episode-item">
                                        <span class="episode-num"><?= $ep['number'] ?></span>
                                        <span><?= $ep['title'] ? e($ep['title']) : 'Серия ' . $ep['number'] ?></span>
                                        <span class="episode-len"><?= $ep['duration'] ?> мин</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    <?php $firstSeason = false; endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Трейлер -->
            <div class="detail-block" id="trailer">
                <h3>Трейлер</h3>
                <?php if ($item['trailer_url']): ?>
                    <div class="video-wrap">
                        <iframe src="<?= e($item['trailer_url']) ?>" title="Трейлер <?= e($item['title']) ?>" allowfullscreen></iframe>
                    </div>
                <?php else: ?>
                    <p class="muted">Трейлер скоро появится.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Похожие -->
<?php if (!empty($similar)): ?>
<section class="section section-dark">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Похожие <?= $isSeries ? 'сериалы' : 'фильмы' ?></h2>
        </div>
        <div class="cards-row">
            <?php foreach ($similar as $sim) render_content_card($sim); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Оценка контента (AJAX, без перезагрузки)
const starSpans = document.querySelectorAll('.star-rate');
starSpans.forEach(s => {
    s.addEventListener('click', async () => {
        const val = s.dataset.val;
        const res = await fetch('rate.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=<?= (int)$id ?>&rating=' + val
        });
        const data = await res.json();
        if (data.ok) {
            starSpans.forEach(x => x.classList.toggle('filled', parseInt(x.dataset.val) <= val));
            document.getElementById('ratingResult').textContent = 'Вы оценили на ' + val;
        } else if (data.login) {
            window.location.href = 'login.php';
        }
    });
});

// Избранное на странице контента
document.querySelector('[data-fav-big]')?.addEventListener('click', async (e) => {
    const btn = e.currentTarget;
    const res = await fetch('fav_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + btn.dataset.favBig
    });
    const data = await res.json();
    if (data.ok) {
        btn.textContent = data.active ? '♥ В избранном' : '♡ В избранное';
    } else if (data.login) {
        window.location.href = 'login.php';
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>