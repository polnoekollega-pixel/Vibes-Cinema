</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-col">
            <a href="index.php" class="logo">
                        <span class="logo-mark">
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"></rect><path d="M10 8.7v6.6l5.5-3.3L10 8.7Z" fill="currentColor" stroke="none"></path></svg>
                        </span>
                        <span>Vibes Cinema</span>
                    </a>
            <p>Онлайн-кинотеатр с тысячами фильмов и сериалов. Смотрите где угодно, когда угодно.</p>
        </div>
        <div class="footer-col">
            <h4>Разделы</h4>
            <a href="catalog.php?type=movie">Фильмы</a>
            <a href="catalog.php?type=series">Сериалы</a>
            <a href="catalog.php">Все категории</a>
            <a href="plans.php">Тарифы</a>
        </div>
        <div class="footer-col">
            <h4>Аккаунт</h4>
            <?php if (current_user()): ?>
                <a href="profile.php">Личный кабинет</a>
                <a href="logout.php">Выйти</a>
            <?php else: ?>
                <a href="login.php">Вход</a>
                <a href="register.php">Регистрация</a>
            <?php endif; ?>
        </div>
        <div class="footer-col">
            <h4>Контакты</h4>
            <span>📧 support@vibescinema.ru</span>
            <span>📞 8 (800) 555-01-01</span>
            <span class="footer-social">🌐 Telegram · VK · YouTube</span>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">© 2026 Vibes Cinema. Учебный проект по дисциплине ПМ.01 «Проектирование и разработка информационных систем».</div>
    </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>