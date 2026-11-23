<?php
/**
 * Vibes Cinema — главная страница сайта
 * Секции: новинки, популярные фильмы и сериалы, категории контента,
 * рекомендации по персональному выбору
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cards.php';

$pdo = db();

// Новинки (последние добавленные)
$new = $pdo->query('SELECT * FROM contents WHERE is_new = 1 ORDER BY created_at DESC LIMIT 8')->fetchAll();

// Популярные фильмы и сериалы
$popular = $pdo->query('SELECT * FROM contents WHERE is_popular = 1 ORDER BY id DESC LIMIT 8')->fetchAll();

// Все категории контента
$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();

// Рекомендации: персональный выбор для авторизованного пользователя,
// для гостей — просто подборка рейтинговых картин
$user = current_user();
if ($user) {
    // Сначала смотрим избранное и историю пользователя
    $st = $pdo->prepare(
        'SELECT c.* FROM contents c
         WHERE c.id IN (SELECT content_id FROM favorites WHERE user_id = ?)
            OR c.id IN (SELECT content_id FROM watch_history WHERE user_id = ?)
         ORDER BY c.id DESC LIMIT 8'
    );
    $st->execute([$user['id'], $user['id']]);
    $recommended = $st->fetchAll();
    if (empty($recommended)) {
        // Если история пуста — предлагаем популярные
        $recommended = $pdo->query('SELECT * FROM contents WHERE is_popular = 1 ORDER BY id DESC LIMIT 8')->fetchAll();
    }
} else {
    $recommended = $pdo->query('SELECT * FROM contents ORDER BY id DESC LIMIT 8')->fetchAll();
}

// Трейлеры недели (популярные с трейлером)
$trailers = $pdo->query('SELECT * FROM contents WHERE trailer_url IS NOT NULL AND trailer_url != "" ORDER BY is_popular DESC, id ASC LIMIT 4')->fetchAll();

// Слайд-шоу hero: популярные картины с постерами
$heroSlides = $pdo->query('SELECT title, poster_url, price FROM contents WHERE poster_url IS NOT NULL AND poster_url != "" AND is_popular = 1 ORDER BY id LIMIT 9')->fetchAll();
if (count($heroSlides) < 2) {
    $heroSlides = $pdo->query('SELECT * FROM contents ORDER BY id ASC LIMIT 9')->fetchAll();
}

// Веер из трёх постеров: левая, центральная и правая карточки
$heroTotal = count($heroSlides);
$heroFan = [
    ['fan-left', $heroTotal > 1 ? $heroTotal - 1 : 0],
    ['fan-center', 0],
    ['fan-right', $heroTotal > 1 ? 1 : 0],
];
$heroPosters = array_map(function ($s) {
    return ['poster' => $s['poster_url'], 'title' => $s['title']];
}, $heroSlides);

$pageTitle = 'Vibes Cinema — онлайн-кинотеатр';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero-блок: слайдер постеров -->
<section class="hero">
    <div class="hero-slides" id="heroSlides">
        <?php foreach ($heroSlides as $i => $hs): ?>
            <div class="hero-slide <?= $i === 0 ? 'active' : '' ?>" style="background-image: url('<?= e($hs['poster_url']) ?>');"></div>
        <?php endforeach; ?>
    </div>
    <div class="container hero-inner">
        <div class="hero-content">
            <h1 class="hero-title">Смотрите фильмы и сериалы <span>в любом месте</span></h1>
            <p class="hero-sub">Тысячи новинок, классика и эксклюзивы Vibes Cinema. Подписка открывает весь каталог без рекламы.</p>
            <div class="hero-actions">
                <a href="catalog.php" class="btn btn-primary btn-lg">Смотреть сейчас</a>
                <a href="plans.php" class="btn btn-ghost btn-lg">Выбрать тариф</a>
            </div>
            <div class="hero-stats">
                <span>🎬 10 000+ фильмов</span>
                <span>📺 3 500+ сериалов</span>
                <span>4K · Full HD · HD</span>
            </div>
        </div>

        <!-- Витрина постеров: три постера одновременно, смена каждые 5 секунд -->
        <?php if (!empty($heroSlides)): ?>
        <div class="hero-showcase">
            <div class="showcase-fan" id="showcaseFan">
                <?php foreach ($heroFan as $slot): ?>
                    <?php $hs = $heroSlides[$slot[1]]; ?>
                    <a class="fan-card <?= $slot[0] ?>" href="catalog.php">
                        <img src="<?= e($hs['poster_url']) ?>" alt="<?= e($hs['title']) ?>">
                        <span class="fan-caption"><?= e($hs['title']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="showcase-dots" id="showcaseDots">
                <?php foreach ($heroSlides as $i => $hs): ?>
                    <i class="<?= $i === 0 ? 'on' : '' ?>"></i>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Трейлеры недели -->
<?php if (!empty($trailers)): ?>
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">🎬 Трейлеры недели</h2>
            <a href="catalog.php" class="section-link">Смотреть все →</a>
        </div>
        <div class="trailers-grid">
            <?php foreach ($trailers as $t): ?>
                <div class="trailer-card">
                    <div class="trailer-video">
                        <iframe src="<?= e($t['trailer_url']) ?>" title="Трейлер <?= e($t['title']) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div class="trailer-info">
                        <h3 class="trailer-title"><?= e($t['title']) ?></h3>
                        <span class="badge-type"><?= $t['type'] === 'series' ? 'Сериал' : 'Фильм' ?></span>
                        <span class="badge-year"><?= (int)$t['release_year'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Категории контента -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Категории контента</h2>
            <a href="catalog.php" class="section-link">Все категории →</a>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="catalog.php?cat=<?= (int)$cat['id'] ?>" class="category-card">
                    <span class="category-icon"><?= e($cat['icon']) ?></span>
                    <span class="category-name"><?= e($cat['name']) ?></span>
                    <span class="category-desc"><?= e($cat['description']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Новинки -->
<section class="section section-dark">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">🔥 Новинки</h2>
            <a href="catalog.php?sort=new" class="section-link">Смотреть все →</a>
        </div>
        <div class="cards-row">
            <?php foreach ($new as $item) render_content_card($item); ?>
        </div>
    </div>
</section>

<!-- Популярные фильмы и сериалы -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">⭐ Популярное</h2>
            <a href="catalog.php?sort=popular" class="section-link">Смотреть все →</a>
        </div>
        <div class="cards-row">
            <?php foreach ($popular as $item) render_content_card($item); ?>
        </div>
    </div>
</section>

<!-- Рекомендации по персональному выбору -->
<section class="section section-dark">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">🎯 Рекомендации для вас</h2>
            <?php if (!$user): ?>
                <a href="login.php" class="section-link">Войдите для персональных рекомендаций →</a>
            <?php endif; ?>
        </div>
        <div class="cards-row">
            <?php foreach ($recommended as $item) render_content_card($item); ?>
        </div>
        <?php if (!$user): ?>
            <p class="rec-hint">💡 Рекомендации строятся на основе ваших оценок, избранного и истории просмотра.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Тарифы (кратко) -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Подписка Vibes Cinema</h2>
            <a href="plans.php" class="section-link">Все тарифы →</a>
        </div>
        <div class="plans-row">
            <div class="plan-card">
                <h3>Базовый</h3>
                <div class="plan-price">299 ₽<span>/мес</span></div>
                <ul><li>Доступ к библиотеке фильмов</li><li>Качество HD</li><li>1 устройство</li></ul>
                <a href="plans.php" class="btn btn-outline">Подробнее</a>
            </div>
            <div class="plan-card recommended">
                <span class="plan-tag">Популярный</span>
                <h3>Стандарт</h3>
                <div class="plan-price">499 ₽<span>/мес</span></div>
                <ul><li>Фильмы и сериалы</li><li>Качество Full HD</li><li>2 устройства</li><li>Без рекламы</li></ul>
                <a href="plans.php" class="btn btn-primary">Подробнее</a>
            </div>
            <div class="plan-card">
                <h3>Премиум</h3>
                <div class="plan-price">799 ₽<span>/мес</span></div>
                <ul><li>Весь контент</li><li>4K Ultra HD + HDR</li><li>4 устройства</li><li>Приоритетная поддержка</li></ul>
                <a href="plans.php" class="btn btn-outline">Подробнее</a>
            </div>
        </div>
    </div>
</section>

<script>
// Hero: фон и веер постеров меняются каждые 5 секунд
(function () {
    var posters = <?= json_encode($heroPosters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var bgSlides = document.querySelectorAll('#heroSlides .hero-slide');
    var cards = document.querySelectorAll('#showcaseFan .fan-card');
    var dots = document.querySelectorAll('#showcaseDots i');
    var total = posters.length;
    if (total < 2) return;

    // Слоты веера: -1 — левая карточка, 0 — центральная, 1 — правая
    var slots = [-1, 0, 1];

    function setPoster(card, item) {
        var img = card.querySelector('img');
        var cap = card.querySelector('.fan-caption');
        if (img.getAttribute('data-src') === item.poster) return;
        img.setAttribute('data-src', item.poster);
        var pre = new Image();
        pre.onload = function () {
            img.src = item.poster;
            img.alt = item.title;
            img.style.opacity = '1';
            if (cap) cap.textContent = item.title;
        };
        img.style.opacity = '0';
        pre.src = item.poster;
    }

    function show(current) {
        bgSlides.forEach(function (el, i) { el.classList.toggle('active', i === current); });
        dots.forEach(function (el, i) { el.classList.toggle('on', i === current); });
        cards.forEach(function (card, i) {
            var idx = ((current + slots[i]) % total + total) % total;
            setPoster(card, posters[idx]);
        });
    }

    var current = 0;
    setInterval(function () {
        current = (current + 1) % total;
        show(current);
    }, 5000);
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>