(function () {
    'use strict';

    const root = document.documentElement;
    const themeButton = document.querySelector('[data-theme-toggle]');
    const savedTheme = localStorage.getItem('color-theme');
    const preferredTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    const applyTheme = function (theme) {
        root.dataset.theme = theme;
        if (themeButton) {
            themeButton.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        }
    };
    applyTheme(savedTheme || preferredTheme);
    if (themeButton) themeButton.addEventListener('click', function () {
        const theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('color-theme', theme);
        applyTheme(theme);
    });

    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-primary-nav]');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            const open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.classList.toggle('is-nav-open', open);
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && nav.classList.contains('is-open')) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('is-nav-open');
                toggle.focus();
            }
        });
    }

    document.querySelectorAll('[data-dismiss-alert]').forEach(function (button) {
        button.addEventListener('click', function () {
            button.closest('[role="alert"], [role="status"]').remove();
        });
    });

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const submit = form.querySelector('[type="submit"]');
            if (submit && !submit.disabled) {
                submit.classList.add('is-loading');
                submit.setAttribute('aria-busy', 'true');
            }
        });
    });

    const scrollButton = document.getElementById('scroll-to-top');
    if (scrollButton) {
        const update = function () {
            scrollButton.classList.toggle('is-visible', window.scrollY > 320);
        };
        window.addEventListener('scroll', update, {passive: true});
        scrollButton.addEventListener('click', function () {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
        update();
    }

    const dashboard = document.querySelector('[data-dashboard-widgets]');
    if (dashboard) {
        let layout = JSON.parse(dashboard.dataset.layout || '{"order":[],"hidden":[]}');
        const picker = document.querySelector('[data-dashboard-picker]');
        const widgets = Array.from(dashboard.querySelectorAll('[data-widget]'));
        const save = function () {
            layout.order = Array.from(dashboard.querySelectorAll('[data-widget]')).map(el => el.dataset.widget);
            layout.hidden = widgets.filter(el => el.hidden).map(el => el.dataset.widget);
            const body = new URLSearchParams(); body.set('layout', JSON.stringify(layout)); body.set(dashboard.dataset.csrfParam, dashboard.dataset.csrfToken);
            fetch(dashboard.dataset.saveUrl, {method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'}, body: body.toString()});
        };
        layout.order.forEach(id => { const el=dashboard.querySelector('[data-widget="'+id+'"]'); if(el) dashboard.appendChild(el); });
        widgets.forEach(el => { el.hidden=(layout.hidden || []).includes(el.dataset.widget); const label=document.createElement('label'); const input=document.createElement('input'); input.type='checkbox'; input.checked=!el.hidden; input.addEventListener('change',()=>{el.hidden=!input.checked; save();}); label.append(input, document.createTextNode(' '+el.dataset.title)); picker.appendChild(label); el.addEventListener('dragstart',()=>el.classList.add('is-dragging')); el.addEventListener('dragend',()=>{el.classList.remove('is-dragging'); save();}); });
        dashboard.addEventListener('dragover', event => { event.preventDefault(); const moving=dashboard.querySelector('.is-dragging'); const target=event.target.closest('[data-widget]'); if(moving && target && moving!==target) dashboard.insertBefore(moving, target); });
        const customize=document.querySelector('[data-dashboard-customize]'); if(customize) customize.addEventListener('click',()=>{picker.hidden=!picker.hidden;});
    }
}());
