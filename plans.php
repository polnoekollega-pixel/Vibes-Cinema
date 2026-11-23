<?php
/**
 * Vibes Cinema — страница тарифов подписки
 * Перечень тарифов с описанием возможностей, кнопка оплаты
 */
require_once __DIR__ . '/includes/functions.php';

$plans = db()->query('SELECT * FROM plans ORDER BY price')->fetchAll();

$pageTitle = 'Тарифы — Vibes Cinema';
include __DIR__ . '/includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Тарифы подписки</h1>
        <p>Выберите подходящий план — отмените в любой момент</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="plans-row plans-full">
            <?php foreach ($plans as $plan): ?>
                <?php
                $features = array_filter(array_map('trim', explode(';', (string)$plan['features'])));
                ?>
                <div class="plan-card <?= $plan['is_recommended'] ? 'recommended' : '' ?>">
                    <?php if ($plan['is_recommended']): ?><span class="plan-tag">Популярный выбор</span><?php endif; ?>
                    <h3><?= e($plan['name']) ?></h3>
                    <div class="plan-price"><?= money($plan['price']) ?><span> / <?= (int)$plan['months'] ?> мес</span></div>
                    <ul class="plan-features">
                        <?php foreach ($features as $f): ?>
                            <li>✔ <?= e($f) ?></li>
                        <?php endforeach; ?>
                        <li>✔ Качество: <?= e($plan['quality']) ?></li>
                        <li>✔ Устройств: <?= (int)$plan['devices'] ?></li>
                    </ul>
                    <a href="checkout.php?plan=<?= (int)$plan['id'] ?>" class="btn <?= $plan['is_recommended'] ? 'btn-primary' : 'btn-outline' ?> btn-block">Оформить</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="info-banner">
            <h3>💡 Как это работает</h3>
            <p>Оплата производится через защищённые платёжные системы. Подписка активируется сразу после оплаты и продлевается автоматически. Вы можете отменить подписку в любой момент в личном кабинете.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>