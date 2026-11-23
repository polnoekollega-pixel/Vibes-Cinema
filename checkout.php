<?php
/**
 * Vibes Cinema — страница оплаты тарифа (checkout)
 * Пользователь выбирает способ оплаты, формируется заказ,
 * оплата имитируется платёжной системой (демо-режим)
 */
require_once __DIR__ . '/includes/functions.php';
require_login();

$pdo = db();
$user = current_user();

// Выбранный тариф
$planId = (int)($_GET['plan'] ?? $_POST['plan'] ?? 0);
$st = $pdo->prepare('SELECT * FROM plans WHERE id = ?');
$st->execute([$planId]);
$plan = $st->fetch();

if (!$plan) {
    header('Location: plans.php');
    exit;
}

// Обработка подтверждения оплаты
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'pay') {
    csrf_check();
    $method = $_POST['method'] ?? 'card';

    // Создаём заказ
    $st = $pdo->prepare('INSERT INTO orders (user_id, plan_id, amount, status, created_at, paid_at) VALUES (?, ?, ?, "paid", NOW(), NOW())');
    $st->execute([$user['id'], $planId, $plan['price']]);

    // Активируем подписку (продлеваем если уже есть активная)
    $expires = date('Y-m-d H:i:s', strtotime('+' . (int)$plan['months'] . ' months'));
    $st = $pdo->prepare(
        'INSERT INTO subscriptions (user_id, plan_id, started_at, expires_at, status) VALUES (?, ?, NOW(), ?, "active")'
    );
    $st->execute([$user['id'], $planId, $expires]);

    // Обновляем роль пользователя на «подписчик»
    $pdo->prepare('UPDATE users SET role = "subscriber" WHERE id = ?')->execute([$user['id']]);
    $_SESSION['user']['role'] = 'subscriber';

    flash('success', 'Оплата прошла успешно! Подписка «' . $plan['name'] . '» активирована.');
    header('Location: profile.php');
    exit;
}

$pageTitle = 'Оплата — Vibes Cinema';
include __DIR__ . '/includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Оплата подписки</h1>
        <p>Заказ подтверждается за пару кликов</p>
    </div>
</section>

<section class="section">
    <div class="container checkout-layout">
        <!-- Сводка заказа -->
        <div class="checkout-summary">
            <h3>Ваш заказ</h3>
            <div class="summary-item">
                <span>Тариф</span>
                <strong><?= e($plan['name']) ?></strong>
            </div>
            <div class="summary-item">
                <span>Срок</span>
                <strong><?= (int)$plan['months'] ?> мес.</strong>
            </div>
            <div class="summary-item">
                <span>Качество</span>
                <strong><?= e($plan['quality']) ?></strong>
            </div>
            <div class="summary-item">
                <span>Устройства</span>
                <strong><?= (int)$plan['devices'] ?></strong>
            </div>
            <div class="summary-total">
                <span>Итого</span>
                <strong><?= money($plan['price']) ?></strong>
            </div>
        </div>

        <!-- Выбор способа оплаты -->
        <div class="checkout-pay">
            <h3>Способ оплаты</h3>
            <form method="post" id="payForm">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="pay">
                <input type="hidden" name="plan" value="<?= (int)$plan['id'] ?>">

                <label class="pay-method">
                    <input type="radio" name="method" value="card" checked>
                    <span class="pay-method-icon">💳</span>
                    <span>
                        <strong>Банковская карта</strong>
                        <small>Visa, MasterCard, МИР</small>
                    </span>
                </label>
                <label class="pay-method">
                    <input type="radio" name="method" value="sbp">
                    <span class="pay-method-icon">📱</span>
                    <span>
                        <strong>СБП</strong>
                        <small>Оплата по QR-коду через приложение банка</small>
                    </span>
                </label>
                <label class="pay-method">
                    <input type="radio" name="method" value="wallet">
                    <span class="pay-method-icon">👛</span>
                    <span>
                        <strong>Электронный кошелёк</strong>
                        <small>ЮMoney, WebMoney</small>
                    </span>
                </label>

                <div class="card-fields" id="cardFields">
                    <div class="form-field">
                        <label>Номер карты</label>
                        <input type="text" id="cardNumber" placeholder="0000 0000 0000 0000" maxlength="19">
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label>Срок действия</label>
                            <input type="text" id="cardExp" placeholder="ММ/ГГ" maxlength="5">
                        </div>
                        <div class="form-field">
                            <label>CVV</label>
                            <input type="text" id="cardCvv" placeholder="123" maxlength="3">
                        </div>
                    </div>
                    <p class="secure-note">🔒 Ваши данные защищены. Это учебный проект — реальное списание не произойдёт.</p>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Оплатить <?= money($plan['price']) ?></button>
            </form>
        </div>
    </div>
</section>

<script>
// Показываем поля карты только при выборе способа «карта»
document.querySelectorAll('input[name="method"]').forEach(r => {
    r.addEventListener('change', () => {
        document.getElementById('cardFields').style.display =
            document.querySelector('input[name="method"]:checked').value === 'card' ? 'block' : 'none';
    });
});
// Простое форматирование номера карты
const cn = document.getElementById('cardNumber');
if (cn) cn.addEventListener('input', () => {
    cn.value = cn.value.replace(/\D/g, '').replace(/(\d{4})(?=\d)/g, '$1 ').slice(0, 19);
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>