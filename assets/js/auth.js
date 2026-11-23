/**
 * Vibes Cinema — клиентская валидация форм регистрации и входа
 * Сообщения об ошибках выводятся без перезагрузки страницы
 */

document.addEventListener('DOMContentLoaded', () => {
    const setError = (id, message) => {
        const el = document.getElementById(id);
        if (el) el.textContent = message || '';
    };

    // Валидация формы регистрации
    const regForm = document.getElementById('regForm');
    if (regForm) {
        regForm.addEventListener('submit', (e) => {
            let ok = true;
            const val = (name) => regForm.querySelector(`[name="${name}"]`).value.trim();

            // Очистка сообщений
            ['last_name', 'first_name', 'login', 'email', 'password', 'password2', 'agree'].forEach(n => setError('err-' + n, ''));

            if (!val('last_name'))  { setError('err-last_name', 'Укажите фамилию'); ok = false; }
            if (!val('first_name')) { setError('err-first_name', 'Укажите имя'); ok = false; }
            if (val('login').length < 3) { setError('err-login', 'Логин не короче 3 символов'); ok = false; }
            if (!/^[a-zA-Z0-9_.-]+$/.test(val('login'))) { setError('err-login', 'Только латиница, цифры, _ . -'); ok = false; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val('email'))) { setError('err-email', 'Некорректный e-mail'); ok = false; }
            if (regForm.querySelector('[name="password"]').value.length < 6) { setError('err-password', 'Пароль не короче 6 символов'); ok = false; }
            if (regForm.querySelector('[name="password"]').value !== regForm.querySelector('[name="password2"]').value) { setError('err-password2', 'Пароли не совпадают'); ok = false; }
            if (!regForm.querySelector('[name="agree"]').checked) { setError('err-agree', 'Подтвердите согласие с правилами'); ok = false; }

            if (!ok) e.preventDefault();
        });
    }

    // Валидация формы входа
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            let ok = true;
            setError('err-login', '');
            setError('err-password', '');
            if (!loginForm.querySelector('[name="login"]').value.trim()) { setError('err-login', 'Введите логин'); ok = false; }
            if (!loginForm.querySelector('[name="password"]').value) { setError('err-password', 'Введите пароль'); ok = false; }
            if (!ok) e.preventDefault();
        });
    }
});