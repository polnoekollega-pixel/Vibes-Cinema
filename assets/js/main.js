/**
 * Vibes Cinema — клиентские скрипты
 * Мобильное меню, выпадающее меню пользователя, избранное (AJAX)
 */

document.addEventListener('DOMContentLoaded', () => {
    // Мобильное меню
    const burger = document.getElementById('burger');
    const nav = document.getElementById('mainNav');
    if (burger && nav) {
        burger.addEventListener('click', () => nav.classList.toggle('open'));
    }

    // Выпадающее меню пользователя
    const userBtn = document.getElementById('userBtn');
    const dropdown = document.getElementById('userDropdown');
    if (userBtn && dropdown) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });
        document.addEventListener('click', () => dropdown.classList.remove('open'));
    }

    // Подсветка активного пункта меню
    const path = (window.location.pathname.split('/').pop() || 'index.php').split('?')[0];
    const params = new URLSearchParams(window.location.search);
    const isCatalog = path === 'catalog.php' || path === 'search.php';
    let activeNav = null;

    if (path === 'index.php') activeNav = 'home';
    else if (path === 'plans.php') activeNav = 'plans';
    else if (path === 'checkout.php') activeNav = 'plans';
    else if (isCatalog) {
        const type = params.get('type');
        activeNav = type === 'series' ? 'series' : 'movies';
    } else if (path === 'content.php') {
        // На странице контента подсвечиваем вкладку по типу (movie/series не передаётся —
        // определим по текущей странице/данным, по умолчанию Фильмы)
        activeNav = document.body.dataset.navType || 'movies';
    }

    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.dataset.nav === activeNav) link.classList.add('active');
    });

    // Избранное: переключение без перезагрузки (AJAX)
    document.querySelectorAll('.card-fav[data-fav]').forEach(el => {
        el.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            const id = el.dataset.fav;
            const res = await fetch('fav_toggle.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            });
            const data = await res.json();
            if (data.ok) {
                el.classList.toggle('active', data.active);
                el.textContent = data.active ? '♥' : '♡';
            } else if (data.login) {
                window.location.href = 'login.php';
            } else {
                alert(data.error || 'Ошибка');
            }
        });
    });
});