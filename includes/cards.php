<?php
/**
 * Vibes Cinema — карточка контента (фильма/сериала)
 * Используется в лентах на главной, в каталоге и результатах поиска
 * @param array $item  строка из таблицы contents
 */
function render_content_card(array $item): void
{
    $isSeries = $item['type'] === 'series';
    $badge = $isSeries ? 'Сериал' : 'Фильм';
    if ($isSeries) {
        $epsTotal = content_episodes_total((int)$item['id']);
        $metaParts = [(int)$item['release_year'], (int)$item['seasons'] . ' сез.'];
        if ($epsTotal > 0) $metaParts[] = $epsTotal . ' сер.';
        $meta = implode(' · ', $metaParts);
    } else {
        $meta = (int)$item['release_year'] . ' · ' . ((int)$item['duration_min'] ? (int)$item['duration_min'] . ' мин.' : '—');
    }
    $poster = $item['poster_url'] ?: 'https://picsum.photos/seed/empty' . $item['id'] . '/400/600';
    $rating = avg_rating((int)$item['id']);
    $fav = in_favorites((int)$item['id']);
    ?>
    <article class="card">
        <a href="content.php?id=<?= (int)$item['id'] ?>" class="card-poster">
            <img src="<?= e($poster) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
            <span class="card-badge"><?= $badge ?></span>
            <span class="card-age"><?= e($item['age_limit']) ?></span>
            <span class="card-play">▶</span>
            <span class="card-fav <?= $fav ? 'active' : '' ?>" data-fav="<?= (int)$item['id'] ?>" title="В избранное"><?= $fav ? '♥' : '♡' ?></span>
            <span class="card-rating">★ <?= $rating ? number_format($rating, 1, '.', ' ') : '—' ?></span>
        </a>
        <div class="card-body">
            <h3 class="card-title"><a href="content.php?id=<?= (int)$item['id'] ?>"><?= e($item['title']) ?></a></h3>
            <div class="card-meta"><?= e($meta) ?></div>
        </div>
    </article>
    <?php
}